<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Interfaces;

/**
 * A class -- typically the randomizer itself -- which supports extensions.
 *
 * This interface defines the methods an extension can access in a randomizer.
 */
interface Extendable
{
    /**
     * Change the mean in a positive or negative direction.
     *
     * @param  float $by
     * @return self
     */
    public function changeMean(float $by): self;
}
