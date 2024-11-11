<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

class CircularDependenciesService
{
    public function __construct(
        CircularDependenciesService $redundantService
    ) {
    }
}
