<?php

namespace Jeanmarcos\DependencyContainer;

use InvalidArgumentException;
use Jeanmarcos\DependencyContainer\Configs\ServiceConfig;
use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;
use Jeanmarcos\DependencyContainer\Exceptions\CircularServiceDependencyException;
use Jeanmarcos\DependencyContainer\Exceptions\ServiceAlreadyDefinedException;
use Jeanmarcos\DependencyContainer\Exceptions\ServiceNotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    public const string SERVICE_ALIAS = 'jeanmarcos_service_container';
    /**
     * @var ServiceConfig[]
     */
    private array $servicesConfig = [];
    /**
     * @var string[]
     */
    private array $aliasToClassMap = [];
    /**
     * @var object[]
     */
    private array $sharedInstances = [];

    /**
     * @var string[]
     */
    private array $instantiationStack = [];

    public function __construct(
        protected ?ContainerConfig $config = null,
        array $servicesConfig = []
    ) {
        if (!$this->config) {
            $this->config = new ContainerConfig();
        }
        $this->setServicesConfig($servicesConfig);
    }

    /**
     * @param ServiceConfig[] $servicesConfig
     */
    public function setServicesConfig(array $servicesConfig): void
    {
        foreach ($servicesConfig as $serviceConfig) {
            $this->setServiceConfig($serviceConfig);
        }
    }

    public function setServiceConfig(ServiceConfig $serviceConfig): void
    {
        $this->validateAlreadyDefinedService($serviceConfig);

        if ($serviceConfig->alias) {
            $this->aliasToClassMap[$serviceConfig->alias] = $serviceConfig->className;
        }
        $this->servicesConfig[$serviceConfig->className] = $serviceConfig;
    }

    private function isContainerId(?string $id): bool
    {
        return $id === self::SERVICE_ALIAS ||
                ltrim($id, '\\') === self::class;
    }

    /**
     * Retrieves an instance by an identifier or class name
     *
     * @param string $id
     *
     * @return $this|mixed
     * @throws ReflectionException
     */
    public function get(string $id): mixed
    {
        $id = ltrim($id, '\\');
        if (
            $this->isContainerId($id)
        ) {
            return $this;
        }
        $serviceConfig = $this->servicesConfig[$this->aliasToClassMap[$id] ?? $id] ?? null;
        if (is_null($serviceConfig)) {
            $serviceConfig = new ServiceConfig(className: $id);
        }
        return $this->makeByServiceConfig($serviceConfig);
    }

    public function has(string $id): bool
    {
        return array_key_exists($this->aliasToClassMap[$id] ?? $id, $this->servicesConfig);
    }

    /**
     * @throws ReflectionException
     */
    private function makeByServiceConfig(ServiceConfig $serviceConfig)
    {
        $className = $serviceConfig->className;
        if (in_array($className, $this->instantiationStack, true)) {
            throw new CircularServiceDependencyException("Circular dependency detected while resolving {$className}");
        }
        $this->instantiationStack[] = $className;

        $isShared = is_null($serviceConfig->shared) ?
                        $this->config->allInstanceSharedByDefault : $serviceConfig->shared;

        if ($isShared && isset($this->sharedInstances[$className])) {
            array_pop($this->instantiationStack);
            return $this->sharedInstances[$className];
        }

        $instance = $this->getInstanceByClass($className);

        if ($isShared) {
            $this->sharedInstances[$className] = $instance;
        }

        array_pop($this->instantiationStack);

        return $instance;
    }


    /**
     * @throws ReflectionException
     */
    private function getInstanceByClass(string $className)
    {
        if (!class_exists($className)) {
            throw new ServiceNotFoundException("Service {$className} not found");
        }
        $reflectionClass = new ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            $instance = new $className();
        } else {
            $dependencies = $this->resolveDependencies($reflectionClass);
            $instance = $reflectionClass->newInstanceArgs($dependencies);
        }
        return $instance;
    }

    /**
     * @param ServiceConfig $serviceConfig
     *
     * @return void
     * @throws ServiceAlreadyDefinedException
     */
    public function validateAlreadyDefinedService(ServiceConfig $serviceConfig): void
    {
        if (isset($this->servicesConfig[$serviceConfig->className])) {
            throw new ServiceAlreadyDefinedException("The class {$serviceConfig->className} is already defined");
        } elseif (isset($this->aliasToClassMap[$serviceConfig->alias])) {
            throw new ServiceAlreadyDefinedException("The identifier {$serviceConfig->alias} is already defined");
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return array
     * @throws ReflectionException
     */
    public function resolveDependencies(ReflectionClass $reflectionClass): array
    {
        $construct = $reflectionClass->getConstructor();
        $parameters = $construct ? $construct->getParameters() : [];
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->resolveDependency($parameter, $reflectionClass);
        }
        return $dependencies;
    }

    /**
     * @throws ReflectionException
     */
    private function resolveDependency(ReflectionParameter $parameter, ReflectionClass $reflectionClass)
    {
        $dependencyType = $parameter->getType();

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull() && $this->config->injectNullForOptionalDependencies) {
            return null;
        }

        if ($dependencyType && !$dependencyType->isBuiltin()) {
            return $this->get($dependencyType->getName());
        }

        throw new InvalidArgumentException(
            "Cannot resolve parameter: '{$parameter->getName()}'" .
                " of Class: '{$reflectionClass->getName()}'"
        );
    }
}
