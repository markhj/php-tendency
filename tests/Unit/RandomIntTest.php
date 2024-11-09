<?php

declare(strict_types=1);

namespace Tests\Unit;

use Markhj\PhpTendency\RandomInt;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\StandardTestCase;

class RandomIntTest extends StandardTestCase
{
    /**
     * In this test, the approach is mimic the calculation carried out
     * inside the ``RandomNumber`` class, when it receives a computed value
     * and selects a number within its min/max range.
     *
     * @param  int  $min Min. value
     * @param  int  $max Max. value
     * @return void
     */
    #[Test]
    #[DataProvider('intProvider')]
    public function int(int $min, int $max): void
    {
        $result = (new RandomInt($min, $max))->compute();

        // Note: Full rounding (of only expected) is to ensure the
        // returned result value is indeed an integer. By leaving the actual value
        // open to decimals, we will see if such are produced, and thus
        // the test will fail.
        $this->assertEquals(
            round($min + ($max - $min) * $result->computed),
            round($result->result, self::FLOAT_ROUNDING),
        );
    }

    public static function intProvider(): array
    {
        return [
            [20, 40],
            [-20, 20],
            [-40, -20],
        ];
    }
}
