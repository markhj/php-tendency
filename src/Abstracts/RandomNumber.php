<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Abstracts;

/**
 * This class serves a common base for integer and float randomizer.
 */
abstract class RandomNumber extends RandomBase
{
    public function __construct(
        protected readonly float | int $min,
        protected readonly float | int $max,
        protected readonly float $stdDeviation = 0.5,
    ) {
    }

    protected function getStandardDeviation(): float
    {
        return $this->stdDeviation;
    }

    protected function interpret(float $computed): float | int
    {
        return $this->min + ($this->max - $this->min) * $computed;
    }
}
