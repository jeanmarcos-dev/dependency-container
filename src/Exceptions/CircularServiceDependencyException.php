<?php

namespace Jeanmarcos\DependencyContainer\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

class CircularServiceDependencyException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
