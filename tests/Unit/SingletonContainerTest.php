<?php

namespace Jeanmarcos\DependencyContainer\Tests\Unit;

use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;
use Jeanmarcos\DependencyContainer\Container;
use Jeanmarcos\DependencyContainer\SingletonContainer;
use PHPUnit\Framework\TestCase;

class SingletonContainerTest extends TestCase
{
    public function testContainerCreation()
    {
        $container = SingletonContainer::getInstance();
        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(SingletonContainer::class, $container);

        $secondContainer = SingletonContainer::getInstance(new ContainerConfig());
        $this->assertSame($container, $secondContainer);
    }
}
