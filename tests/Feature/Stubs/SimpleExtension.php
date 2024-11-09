<?php

declare(strict_types=1);

namespace Tests\Feature\Stubs;

use Markhj\PhpTendency\Attributes\Expose;
use Markhj\PhpTendency\Interfaces\Extendable;
use Markhj\PhpTendency\Interfaces\Extension;

class SimpleExtension implements Extension
{
    /**
     * Sample function to be used in test cases.
     *
     * @param  Extendable $random
     * @param  float      $change
     * @return Extendable
     */
    #[Expose]
    public function myFunc(
        Extendable $random,
        float $change,
    ): Extendable {
        return $random->changeMean($change);
    }
}
