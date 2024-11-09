<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Exceptions;

class InvalidFirstArgumentException extends \Exception
{
    protected $message = 'The first argument of an exposed function must be an Extendable.';
}
