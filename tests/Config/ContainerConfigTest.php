<?php

namespace Jeanmarcos\DependencyContainer\Tests\Config;

use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;
use PHPUnit\Framework\TestCase;

class ContainerConfigTest extends TestCase
{
    public function testDefaultInstanceSharing()
    {
        $config = new ContainerConfig();
        $this->assertFalse($config->allInstanceSharedByDefault);
    }
    public function testInstanceSharing()
    {
        $config = new ContainerConfig(allInstanceSharedByDefault: true);
        $this->assertTrue($config->allInstanceSharedByDefault);
    }
}
