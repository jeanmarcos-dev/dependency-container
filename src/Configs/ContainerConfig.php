<?php

namespace Jeanmarcos\DependencyContainer\Configs;

class ContainerConfig
{
    public function __construct(
        public bool $allInstanceSharedByDefault = false,
        public bool $injectNullForOptionalDependencies = true
    ) {
    }
}
