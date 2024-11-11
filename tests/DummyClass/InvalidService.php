<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

class InvalidService
{
    public function __construct(public int $requiredParam)
    {
    }
}
