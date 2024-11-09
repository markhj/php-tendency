<?php

declare(strict_types=1);

namespace Tests\Feature\Stubs;

use Markhj\PhpTendency\Attributes\Expose;
use Markhj\PhpTendency\Interfaces\Extendable;
use Markhj\PhpTendency\Interfaces\Extension;

class InvalidReturnType implements Extension
{
    /**
     * @param  Extendable $randomizer
     * @param  float      $change
     * @return float
     *
     * @note ExtendedRandomizer must be the first parameter.
     *      It is intentionally omitted here, such that this
     *      problem can be tested.
     */
    #[Expose]
    public function myFunc(Extendable $randomizer, float $change): float
    {
        return 0;
    }
}
