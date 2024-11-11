<?php

namespace Jeanmarcos\DependencyContainer\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceConfigException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
