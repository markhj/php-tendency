<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Exceptions;

class InvalidReturnTypeException extends \Exception
{

    /**
     * @var string Exception message
     */
    protected $message = 'Exposed methods must return an Extendable.';
}
