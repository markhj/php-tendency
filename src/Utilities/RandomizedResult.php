<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Utilities;

readonly class RandomizedResult
{
    /**
     * The presence of $mean and $computed make it significantly easier
     * to verify and test randomized results, because even if the randomization
     * falls far outside the standard deviation (which is fully allowed),
     * we can still verify various mechanics of the class, without interpreting
     * the computed value.
     *
     * @param float $mean
     * @param float $computed
     * @param mixed $result
     */
    public function __construct(
        public float $mean,
        public float $computed,
        public mixed $result,
    ) {
    }
}
