<?php

namespace Jeanmarcos\DependencyContainer\Tests\Unit;

use InvalidArgumentException;
use Jeanmarcos\DependencyContainer\Container;
use Jeanmarcos\DependencyContainer\Configs\ServiceConfig;
use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;
use Jeanmarcos\DependencyContainer\Exceptions\ServiceAlreadyDefinedException;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\FooService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\BarService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\DefaultService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\OptionalService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\InvalidService;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testSetServiceConfigRegistersService(): void
    {
        $container = new Container();
        $serviceConfig = new ServiceConfig(className: FooService::class, alias: 'foo');

        $container->setServiceConfig($serviceConfig);

        $this->assertTrue($container->has(FooService::class));
        $this->assertTrue($container->has('foo'));
    }

    public function testHasReturnsTrueForExistingService(): void
    {
        $container = new Container();
        $serviceConfig = new ServiceConfig(className: FooService::class);
        $container->setServiceConfig($serviceConfig);

        $this->assertTrue($container->has(FooService::class));
        $this->assertFalse($container->has('NonExistentService'));
    }

    public function testThrowsExceptionForUnresolvableParameter(): void
    {
        $container = new Container();
        $reflectionClass = new \ReflectionClass(InvalidService::class);
        $parameter = $reflectionClass->getConstructor()->getParameters()[0];

        $reflectionMethod = new \ReflectionMethod(Container::class, 'resolveDependency');
        $reflectionMethod->setAccessible(true);

        $this->expectException(InvalidArgumentException::class);
        $reflectionMethod->invoke($container, $parameter, $reflectionClass);
    }

    public function testSetServiceConfigThrowsExceptionForDuplicateService(): void
    {
        $container = new Container();
        $serviceConfig = new ServiceConfig(className: FooService::class);

        $container->setServiceConfig($serviceConfig);

        $this->expectException(ServiceAlreadyDefinedException::class);
        $container->setServiceConfig($serviceConfig);
    }

    public function testValidateAlreadyDefinedServiceThrowsExceptionForDuplicateClass(): void
    {
        $container = new Container();
        $serviceConfig = new ServiceConfig(className: FooService::class);

        $container->setServiceConfig($serviceConfig);

        $this->expectException(ServiceAlreadyDefinedException::class);
        $container->validateAlreadyDefinedService($serviceConfig);
    }

    public function testIsContainerIdWithServiceAlias(): void
    {
        $container = new Container();
        $reflectionMethod = new \ReflectionMethod(Container::class, 'isContainerId');
        $reflectionMethod->setAccessible(true);

        $this->assertTrue($reflectionMethod->invoke($container, Container::SERVICE_ALIAS));
    }

    public function testIsContainerIdWithContainerClassName(): void
    {
        $container = new Container();
        $reflectionMethod = new \ReflectionMethod(Container::class, 'isContainerId');
        $reflectionMethod->setAccessible(true);

        $this->assertTrue($reflectionMethod->invoke($container, Container::class));
    }

    public function testIsContainerIdWithDifferentId(): void
    {
        $container = new Container();
        $reflectionMethod = new \ReflectionMethod(Container::class, 'isContainerId');
        $reflectionMethod->setAccessible(true);

        $this->assertFalse($reflectionMethod->invoke($container, FooService::class));
    }


    public function testValidateAlreadyDefinedServiceThrowsExceptionForDuplicateAlias(): void
    {
        $container = new Container();
        $serviceConfig1 = new ServiceConfig(className: FooService::class, alias: 'foo');
        $serviceConfig2 = new ServiceConfig(className: BarService::class, alias: 'foo');

        $container->setServiceConfig($serviceConfig1);

        $this->expectException(ServiceAlreadyDefinedException::class);
        $container->validateAlreadyDefinedService($serviceConfig2);
    }

    public function testResolveDependencyReturnsDefaultValue(): void
    {
        $container = new Container();
        $reflectionClass = new \ReflectionClass(DefaultService::class);
        $parameter = $reflectionClass->getConstructor()->getParameters()[0];

        $reflectionMethod = new \ReflectionMethod(Container::class, 'resolveDependency');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals('default', $reflectionMethod->invoke($container, $parameter, $reflectionClass));
    }

    public function testResolveDependencyReturnsNullForOptionalDependency(): void
    {
        $config = new ContainerConfig(injectNullForOptionalDependencies: true);
        $container = new Container($config);
        $reflectionClass = new \ReflectionClass(OptionalService::class);
        $parameter = $reflectionClass->getConstructor()->getParameters()[0];

        $reflectionMethod = new \ReflectionMethod(Container::class, 'resolveDependency');
        $reflectionMethod->setAccessible(true);

        $this->assertNull($reflectionMethod->invoke($container, $parameter, $reflectionClass));
    }

    public function testResolveDependencyResolvesClassDependency(): void
    {
        $container = new Container();
        $container->setServiceConfig(new ServiceConfig(className: FooService::class));
        $reflectionClass = new \ReflectionClass(BarService::class);
        $parameter = $reflectionClass->getConstructor()->getParameters()[0];

        $reflectionMethod = new \ReflectionMethod(Container::class, 'resolveDependency');
        $reflectionMethod->setAccessible(true);

        $dependency = $reflectionMethod->invoke($container, $parameter, $reflectionClass);
        $this->assertInstanceOf(FooService::class, $dependency);
    }

    public function testResolveDependencyThrowsExceptionForUnresolvableParameter(): void
    {
        $container = new Container();
        $reflectionClass = new \ReflectionClass(InvalidService::class);
        $parameter = $reflectionClass->getConstructor()->getParameters()[0];

        $reflectionMethod = new \ReflectionMethod(Container::class, 'resolveDependency');
        $reflectionMethod->setAccessible(true);

        $this->expectException(InvalidArgumentException::class);
        $reflectionMethod->invoke($container, $parameter, $reflectionClass);
    }


    public function testHasReturnsTrueForRegisteredService(): void
    {
        $container = new Container();
        $serviceConfig = new ServiceConfig(className: FooService::class);

        $container->setServiceConfig($serviceConfig);

        $this->assertTrue($container->has(FooService::class));
    }

    public function testHasReturnsFalseForUnregisteredService(): void
    {
        $container = new Container();
        $this->assertFalse($container->has('NonExistentService'));
    }
}
