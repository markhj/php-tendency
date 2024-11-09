<?php

declare(strict_types=1);

namespace Tests\Unit;

use Markhj\PhpTendency\RandomBool;
use PHPUnit\Framework\Attributes\Test;
use Tests\StandardTestCase;

class RandomBoolTest extends StandardTestCase
{
    /**
     * Verify that the outcome of true/false matches the computed value.
     * When higher than 0.5 the value is true.
     *
     * @note There is a tiny-tiny risk that this method can trigger
     *      an incorrect result, if the return value falls very far outside
     *      the standard deviation.
     *
     *      However, at this stage, and given the extremely low risk,
     *      it has been deemed unnecessary to implement a mock, simply to secure
     *      what is at best the theoretical longings of a completionist.
     *
     * @return void
     */
    #[Test]
    public function bool(): void
    {
        $this->assertFalse((new RandomBool(0.01))->changeMean(-10.0)->compute()->result);
        $this->assertTrue((new RandomBool(0.01))->changeMean(10.0)->compute()->result);
    }
}
