<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Abstracts;

use Markhj\PhpTendency\Attributes\Expose;
use Markhj\PhpTendency\Exceptions\InvalidFirstArgumentException;
use Markhj\PhpTendency\Exceptions\InvalidReturnTypeException;
use Markhj\PhpTendency\Interfaces\Extendable;
use Markhj\PhpTendency\Interfaces\Extension;
use Markhj\PhpTendency\Utilities\ExtensionRegistry;
use Markhj\PhpTendency\Utilities\RandomizedResult;

abstract class RandomBase implements Extendable
{
    /**
     * Mean generally should be between 0 and 1.
     * However, this isn't strictly enforced, because there are scenarios
     * where something becomes so likely or unlikely, the scales must
     * be seriously tipped in one direction.
     *
     * The computed random value will be clamped between 0 and 1.
     *
     * @var float
     */
    private float $mean = 0.5;

    /**
     * @var ExtensionRegistry|null
     */
    private null | ExtensionRegistry $extensionRegistry = null;

    /**
     * Child class must implement a function which interprets an
     * outcome, based on the computed random value (which is always
     * between 0 and 1).
     *
     * Want to return bool, float, an item of an array, or something
     * else? The sky is the limit.
     *
     * @param  float $computed
     * @return mixed
     */
    abstract protected function interpret(float $computed): mixed;

    /**
     * Returns the standard deviation.
     * Can be overridden by child classes which want to either
     * strictly enforce a std. deviation, or offer it as part
     * of their own constructor.
     *
     * @return float
     */
    protected function getStandardDeviation(): float
    {
        return 0.5;
    }

    /**
     * Magic method which intercepts method calls.
     * Used to interact with exposed methods of registered extensions.
     *
     * @param  string     $name
     * @param  array      $arguments
     * @return $this|null
     */
    public function __call(string $name, array $arguments)
    {
        // If $name is an actual method defined on the class, we jump right ahead.
        // Extensions should not be able to intercept these.
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        // If the method is registered as an exposed method, we'll
        // ask the ExtensionRegistry to return the result of said function call.
        if ($this->extensionRegistry?->has($name)) {
            $this->extensionRegistry->call($this, $name, $arguments);

            // Chain-ability
            return $this;
        }

        return null;
    }

    /**
     * Alter the median in negative or positive direction.
     *
     * @param  float $by
     * @return $this
     */
    public function changeMean(float $by): RandomBase
    {
        $this->mean += $by;

        return $this;
    }

    /**
     * Add an extension and expose the methods marked with ``#[Expose]``
     * attribute.
     *
     * @param  Extension                     $instance
     * @return $this
     * @throws InvalidFirstArgumentException
     * @throws InvalidReturnTypeException
     */
    public function extend(Extension $instance): RandomBase
    {
        // Why is this not in the constructor?
        // Two reasons:
        //      - No need to initialize it if no extensions are added
        //      - But more importantly, if possible, we'd like to avoid
        //          requiring child classes to reference the parent constructor,
        //          as that can lead to confusion when forgotten.
        if (is_null($this->extensionRegistry)) {
            $this->extensionRegistry = new ExtensionRegistry();
        }

        $this->extensionRegistry->register($instance);

        // Set up a reflection instance so we can retrieve its methods
        $class = new \ReflectionClass($instance);

        foreach ($class->getMethods() as $method) {
            foreach ($method->getAttributes() as $attribute) {
                if ($attribute->getName() === Expose::class) {
                    // The first argument of an exposed method must take an instance of ``Extendable``,
                    // because the instance of this class will be injected, such that the ``changeMean``
                    // method can be accessed.
                    if ($method->getParameters()[0]?->getType()->getName() !==
                            Extendable::class) {
                        throw new InvalidFirstArgumentException();
                    }

                    // Method must return the ``Extendable``
                    if ($method->getReturnType()->getName() !== Extendable::class) {
                        throw new InvalidReturnTypeException();
                    }

                    // Register this method as exposed
                    $this->extensionRegistry->expose($instance, $method->getName());
                }
            }
        }

        return $this;
    }

    /**
     * @return RandomizedResult
     *
     * @note Numbers are defined on float-form (i.e. ``1.0`` instead of ``1``) to ensure
     *      the clamp function doesn't interpret the value as integer, and as thus performs
     *      the clamping at integer-level.
     */
    public function compute(): RandomizedResult
    {
        $deviation = $this->clamp($this->getStandardDeviation());
        $computed = $this->biased($deviation);

        return new RandomizedResult(
            mean: $this->mean,
            computed: $computed,
            result: $this->interpret($computed),
        );
    }

    /**
     * Clamps a given number to ensure it falls within the range of 0.0 to 1.0.
     *
     * @param  float $number
     * @return float
     */
    private function clamp(float $number): float
    {
        return max(0.0, min(1.0, $number));
    }

    /**
     * Generates a normal distributed number based on the Box-Muller transform,
     * scales it to the desired mean and standard deviation, and clamps it within bounds.
     *
     * @see https://en.wikipedia.org/wiki/Box–Muller_transform
     *
     * @param  float $stdDeviation
     * @return float
     */
    private function biased(float $stdDeviation): float
    {
        // Box-Muller transform to generate a normal distribution
        $u1 = mt_rand() / mt_getrandmax();
        $u2 = mt_rand() / mt_getrandmax();

        // Generate a standard normally distributed number
        $z = sqrt(-2 * log($u1)) * cos(2 * M_PI * $u2);

        return $this->clamp(0.5 + ($z * $stdDeviation + $this->mean) / 2);
    }
}
