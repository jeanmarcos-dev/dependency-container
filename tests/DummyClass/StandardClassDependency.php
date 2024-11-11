<?php

namespace Jeanmarcos\DependencyContainer\Tests\DummyClass;

use DateTime;

class StandardClassDependency
{
    public function __construct(
        public DateTime $dateTime1,
        public \DateTime $dateTime2
    ) {
    }
}
