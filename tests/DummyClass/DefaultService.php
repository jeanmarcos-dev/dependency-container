<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

class DefaultService
{
    public function __construct(public string $defaultParam = 'default')
    {
    }
}
