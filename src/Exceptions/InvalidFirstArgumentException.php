<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Exceptions;

class InvalidFirstArgumentException extends \Exception
{

    /**
     * @var string Exception message
     */
    protected $message = 'The first argument of an exposed function must be an Extendable.';
}
