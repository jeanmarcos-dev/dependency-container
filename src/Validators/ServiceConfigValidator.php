<?php

namespace Jeanmarcos\DependencyContainer\Validators;

use Jeanmarcos\DependencyContainer\Configs\ServiceConfig;
use Jeanmarcos\DependencyContainer\Container;
use Jeanmarcos\DependencyContainer\Exceptions\ServiceConfigException;
use Jeanmarcos\DependencyContainer\SingletonContainer;

class ServiceConfigValidator
{
    /**
     * @var string[]
     */
    private const array RESERVED_ALIAS = [
        Container::SERVICE_ALIAS
    ];

    /**
     * @var string[]
     */
    private const array RESERVED_CLASSES = [
        Container::class,
        SingletonContainer::class,
    ];

    /**
     * @param ServiceConfig $config
     * @return void
     * @throws ServiceConfigException
     */
    public static function validate(ServiceConfig $config): void
    {
        if (self::isReservedClassName($config->className)) {
            throw new ServiceConfigException("The class '{$config->className}' is reserved");
        }

        if ($config->alias && self::isReservedAlias($config->alias)) {
            throw new ServiceConfigException("The alias '{$config->alias}' is reserved");
        }
    }

    private static function isReservedClassName(?string $className): bool
    {
        return in_array(ltrim($className ?? '', '\\'), self::RESERVED_CLASSES, true);
    }

    private static function isReservedAlias(?string $alias): bool
    {
        return in_array($alias, self::RESERVED_ALIAS, true);
    }
}
