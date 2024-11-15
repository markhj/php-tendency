<?php

declare(strict_types=1);

namespace Markhj\PhpTendency;

use Markhj\PhpTendency\Abstracts\RandomNumber;

class RandomFloat extends RandomNumber
{
    /**
     * This constructor "tightens" the requirements of the parent constructor
     * by narrowing from ``float | int`` to just float, thus securing the intention
     * of this class.
     *
     * @param float $min Min. value
     * @param float $max Max. value
     * @param float $stdDeviation (optional) standard deviation
     */
    public function __construct(float $min, float $max, float $stdDeviation = 0.5)
    {
        parent::__construct($min, $max, $stdDeviation);
    }

    /**
     * Use the computed as a "percentage-like" indicator to select
     * a value between min and max.
     *
     * @param  float $computed Computed random value
     * @return float Float between min. and max. based on the computed value
     */
    protected function interpret(float $computed): float
    {
        return $this->min + ($this->max - $this->min) * $computed;
    }
}
