<?php

namespace Jeanmarcos\DependencyContainer\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceAlreadyDefinedException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
