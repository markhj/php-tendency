<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class StandardTestCase extends TestCase
{
    /**
     * To avoid issues at a really low decimal, we accept a test
     * when it produces a result equal to the nth decimals, where
     * n is defined here.
     */
    protected const int FLOAT_ROUNDING = 4;
}
