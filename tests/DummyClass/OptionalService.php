<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

class OptionalService
{
    public function __construct(public ?string $optionalParam = null)
    {
    }
}
