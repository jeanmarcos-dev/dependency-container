<?php

namespace Jeanmarcos\DependencyContainer\Resources;

class Helper
{
    public function __construct(
        array $y,
        private $x = 1
    ) {
    }
    public function someStuff(): string
    {
        return "Some Stuff";
    }
}
