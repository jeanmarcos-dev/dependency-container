<?php

namespace Jeanmarcos\DependencyContainer\Tests\Config;

use Jeanmarcos\DependencyContainer\Configs\ServiceConfig;
use Jeanmarcos\DependencyContainer\Container;
use Jeanmarcos\DependencyContainer\Exceptions\ServiceConfigException;
use PHPUnit\Framework\TestCase;

class ServiceConfigTest extends TestCase
{
    public function testValidateReservedContainerClassName(): void
    {
        $this->expectException(ServiceConfigException::class);
        new ServiceConfig(className: Container::class);
    }
    public function testValidateReservedContainerAlias(): void
    {
        $this->expectException(ServiceConfigException::class);
        new ServiceConfig(className: 'dummy', alias: Container::SERVICE_ALIAS);
    }
}
