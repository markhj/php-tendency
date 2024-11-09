<?php

declare(strict_types=1);

namespace Tests\Feature\Stubs;

use Markhj\PhpTendency\Attributes\Expose;
use Markhj\PhpTendency\Interfaces\Extendable;
use Markhj\PhpTendency\Interfaces\Extension;
use Markhj\PhpTendency\RandomFloat;

class InvalidFirstParameter implements Extension
{
    /**
     * @param  float      $change
     * @return Extendable
     *
     * @note ExtendedRandomizer must be the first parameter.
     *      It is intentionally omitted here, such that this
     *      problem can be tested.
     */
    #[Expose]
    public function myFunc(float $change): Extendable
    {
        // This part is simply to silence the error regarding
        // return type. Since the required first parameter isn't present
        // it cannot be returned.
        return (new RandomFloat(0, 1));
    }
}
