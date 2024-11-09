<?php

declare(strict_types=1);

namespace Tests\Feature;

use Markhj\PhpTendency\Exceptions\InvalidFirstArgumentException;
use Markhj\PhpTendency\Exceptions\InvalidReturnTypeException;
use Markhj\PhpTendency\RandomFloat;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Stubs\InvalidFirstParameter;
use Tests\Feature\Stubs\InvalidReturnType;
use Tests\Feature\Stubs\SimpleExtension;
use Tests\StandardTestCase;

class ExtensionTest extends StandardTestCase
{
    /**
     * In this scenario, we register the ``SimpleExtension`` extension
     * to a ``RandomFloat``, and attempt to call the exposed ``myFunc``.
     *
     * To verify that the extension has an effect on the randomizer, we
     * fortunately don't have to do odd guesswork based on a random value.
     * We're so fortunate that the ``RandomizedResult`` contains the mean
     * value, which is the one we're interested in.
     *
     * @return void
     * @throws InvalidFirstArgumentException
     * @throws InvalidReturnTypeException
     *
     * @note We round the result because floating-point values can have precision issues
     *        at very low decimals due to the way floating-point arithmetic works
     */
    #[Test]
    public function extend(): void
    {
        $random = new RandomFloat(0, 1);

        // Use the extension (which provides myFunc) to increase the mean
        $random->extend(new SimpleExtension())->myFunc(0.4);
        $this->assertEquals(0.9, round($random->compute()->mean, self::FLOAT_ROUNDING));

        // And let's try the other way, as well
        $random->myFunc(-0.6);
        $this->assertEquals(0.3, round($random->compute()->mean, self::FLOAT_ROUNDING));
    }

    /**
     * Method to test the scenario where the first parameter is invalid.
     *
     * @return void
     * @throws InvalidReturnTypeException
     */
    #[Test]
    public function invalidFirstParameter(): void
    {
        $this->expectException(InvalidFirstArgumentException::class);

        $random = new RandomFloat(0, 1);
        $random->extend(new InvalidFirstParameter());
    }

    /**
     * Method to test the scenario where the return type is invalid.
     *
     * @return void
     * @throws InvalidFirstArgumentException
     */
    #[Test]
    public function invalidReturnType(): void
    {
        $this->expectException(InvalidReturnTypeException::class);

        $random = new RandomFloat(0, 1);
        $random->extend(new InvalidReturnType());
    }
}
