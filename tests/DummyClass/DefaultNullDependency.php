<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

class DefaultNullDependency
{
    public function __construct(
        public ?FooService $fooService = null
    ) {
    }
}
