<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

class BarService
{
    public function __construct(public FooService $fooService)
    {
    }
}
