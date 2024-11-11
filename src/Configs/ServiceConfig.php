<?php

namespace Jeanmarcos\DependencyContainer\Configs;

use Jeanmarcos\DependencyContainer\Validators\ServiceConfigValidator;

class ServiceConfig
{
    public function __construct(
        public string $className,
        public ?string $alias = null,
        public ?bool $shared = null
    ) {
        $this->className = ltrim($this->className, '\\');
        ServiceConfigValidator::validate($this);
    }
}
