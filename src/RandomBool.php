<?php

declare(strict_types=1);

namespace Markhj\PhpTendency;

use Markhj\PhpTendency\Abstracts\RandomBase;

/**
 * Returns true when the mean value is above 0.5, otherwise false.
 */
class RandomBool extends RandomBase
{
    /**
     * Create a new randomized boolean.
     *
     * @param float $stdDeviation
     */
    public function __construct(
        private readonly float $stdDeviation = 0.5,
    ) {
    }

    /**
     * Retrieve the standard deviation value.
     * If not set, the default of 0.5 is returned.
     *
     * @return float
     */
    protected function getStandardDeviation(): float
    {
        return $this->stdDeviation;
    }

    /**
     * Interpret the computed random value, such that if
     * it's above 0.5, then it's considered true. Otherwise, false.
     *
     * @param  float $computed
     * @return bool
     */
    protected function interpret(float $computed): bool
    {
        return $computed > 0.5;
    }
}
