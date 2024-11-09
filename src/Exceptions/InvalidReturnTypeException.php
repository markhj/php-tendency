<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Exceptions;

class InvalidReturnTypeException extends \Exception
{
    protected $message = 'Exposed methods must return an Extendable.';
}
