<?php

declare(strict_types=1);

namespace Markhj\PhpTendency;

use Markhj\PhpTendency\Abstracts\RandomNumber;

class RandomInt extends RandomNumber
{
    /**
     * This constructor "tightens" the requirements of the parent constructor
     * by narrowing from ``float | int`` to ``int``, thus securing the intention
     * of this class.
     *
     * @param int   $min
     * @param int   $max
     * @param float $stdDeviation
     */
    public function __construct(int $min, int $max, float $stdDeviation = 0.5)
    {
        parent::__construct($min, $max, $stdDeviation);
    }

    /**
     * Use the computed as a "percentage-like" indicator to select
     * a value between min and max.
     *
     * @param  float $computed
     * @return int
     */
    protected function interpret(float $computed): int
    {
        return $this->min + round(($this->max - $this->min) * $computed);
    }
}
