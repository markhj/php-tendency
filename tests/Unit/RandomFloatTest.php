<?php

declare(strict_types=1);

namespace Tests\Unit;

use Markhj\PhpTendency\RandomFloat;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\StandardTestCase;

class RandomFloatTest extends StandardTestCase
{
    /**
     * In this test, the approach is mimic the calculation carried out
     * inside the ``RandomNumber`` class, when it receives a computed value
     * and selects a number within its min/max range.
     *
     * @param  float $min
     * @param  float $max
     * @return void
     */
    #[Test]
    #[DataProvider('floatProvider')]
    public function float(float $min, float $max): void
    {
        $result = (new RandomFloat($min, $max))->compute();
        $this->assertEquals(
            round($min + ($max - $min) * $result->computed, self::FLOAT_ROUNDING),
            round($result->result, self::FLOAT_ROUNDING),
        );
    }

    public static function floatProvider(): array
    {
        return [
            [20, 40],
            [-20, 20],
            [-40, -20],
        ];
    }
}
