<?php

namespace Jeanmarcos\DependencyContainer;

use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;

class SingletonContainer extends Container
{
    private static ?SingletonContainer $instance = null;

    private function __construct(
        ?ContainerConfig $config = null,
        array $servicesConfig = []
    ) {
        parent::__construct($config, $servicesConfig);
    }

    public static function getInstance(
        ?ContainerConfig $config = null,
        array $servicesConfig = []
    ): SingletonContainer {
        if (self::$instance === null) {
            self::$instance = new SingletonContainer($config, $servicesConfig);
        }
        return self::$instance;
    }
}
