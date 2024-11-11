<?php

namespace Jeanmarcos\DependencyContainer\Tests\Integration;

use Jeanmarcos\DependencyContainer\Container;
use Jeanmarcos\DependencyContainer\Configs\ServiceConfig;
use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;
use Jeanmarcos\DependencyContainer\Exceptions\CircularServiceDependencyException;
use Jeanmarcos\DependencyContainer\Exceptions\ServiceNotFoundException;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\BarService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\DefaultService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\FooService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\OptionalService;
use Jeanmarcos\DependencyContainer\Tests\DummyClass\CircularDependenciesService;
use PHPUnit\Framework\TestCase;

class ContainerIntegrationTest extends TestCase
{
    public function testContainerResolvesSimpleService(): void
    {
        $container = new Container();
        $container->setServiceConfig(new ServiceConfig(className: FooService::class));

        $fooService = $container->get(FooService::class);
        $this->assertInstanceOf(FooService::class, $fooService);
    }

    public function testContainerResolvesServiceWithDependencies(): void
    {
        $container = new Container();
        $container->setServiceConfig(new ServiceConfig(className: FooService::class));
        $container->setServiceConfig(new ServiceConfig(className: BarService::class));

        $barService = $container->get(BarService::class);
        $this->assertInstanceOf(BarService::class, $barService);
        $this->assertInstanceOf(FooService::class, $barService->fooService);
    }

    public function testContainerHandlesOptionalDependencyWithNull(): void
    {
        $config = new ContainerConfig(injectNullForOptionalDependencies: true);
        $container = new Container($config);
        $container->setServiceConfig(new ServiceConfig(className: OptionalService::class));

        $optionalService = $container->get(OptionalService::class);
        $this->assertInstanceOf(OptionalService::class, $optionalService);
        $this->assertNull($optionalService->optionalParam);
    }

    public function testContainerHandlesDefaultParameter(): void
    {
        $container = new Container();
        $container->setServiceConfig(new ServiceConfig(className: DefaultService::class));

        $defaultService = $container->get(DefaultService::class);
        $this->assertInstanceOf(DefaultService::class, $defaultService);
        $this->assertEquals('default', $defaultService->defaultParam);
    }

    public function testContainerThrowsExceptionForUnresolvableService(): void
    {
        $container = new Container();
        $this->expectException(ServiceNotFoundException::class);
        $container->get('NonExistentService');
    }

    public function testContainerThrowsExceptionForCircularDependency(): void
    {
        $container = new Container();
        $container->setServiceConfig(new ServiceConfig(className: CircularDependenciesService::class));

        $this->expectException(CircularServiceDependencyException::class);
        $container->get(CircularDependenciesService::class);
    }

    public function testSharedInstancesReturnSameObject(): void
    {
        $config = new ContainerConfig(allInstanceSharedByDefault: true);
        $container = new Container($config);
        $container->setServiceConfig(new ServiceConfig(className: FooService::class));

        $fooService1 = $container->get(FooService::class);
        $fooService2 = $container->get(FooService::class);

        $this->assertSame($fooService1, $fooService2);
    }

    public function testNonSharedInstancesReturnDifferentObjects(): void
    {
        $config = new ContainerConfig(allInstanceSharedByDefault: false);
        $container = new Container($config);
        $container->setServiceConfig(new ServiceConfig(className: FooService::class));

        $fooService1 = $container->get(FooService::class);
        $fooService2 = $container->get(FooService::class);

        $this->assertNotSame($fooService1, $fooService2);
    }

    public function testContainerResolvesAlias(): void
    {
        $container = new Container();
        $container->setServiceConfig(new ServiceConfig(className: FooService::class, alias: 'foo_alias'));

        $fooService = $container->get('foo_alias');
        $this->assertInstanceOf(FooService::class, $fooService);
    }

    public function testResolvesStandardPHPClass(): void
    {
        $container = new Container();
        $instance = $container->get('DateTime');

        $this->assertInstanceOf(\DateTime::class, $instance);
    }
}
