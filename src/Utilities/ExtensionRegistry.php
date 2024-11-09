<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Utilities;

use Markhj\PhpTendency\Interfaces\Extendable;
use Markhj\PhpTendency\Interfaces\Extension;

/**
 * Container and handler of extensions.
 * Intended to be instantiated inside ``RandomBase``.
 */
class ExtensionRegistry
{
    /**
     * List of registered instance (classes which extend ``Extension``)
     * @var array
     */
    private array $instances = [];

    /**
     * List of exposed methods (and the instance they belong to).
     *
     * @var array
     */
    private array $exposedMethods = [];

    /**
     * Call a method on its associated instance, and provide the
     * necessary arguments (i.e. the randomizer itself alongside the
     * arguments provided in runtime).
     *
     * @param  Extendable $randomizer
     * @param  string     $method
     * @param  array      $args
     * @return mixed
     */
    public function call(
        Extendable $randomizer,
        string $method,
        array $args,
    ) {
        $instance = $this->instances[$this->exposedMethods[$method]];

        return $instance->{$method}($randomizer, ...$args);
    }

    /**
     * Returns true, if the method has been registered as exposed.
     *
     * @param  string $method
     * @return bool
     */
    public function has(string $method): bool
    {
        return array_key_exists($method, $this->exposedMethods);
    }

    /**
     * Register an instance of an extension.
     *
     * @param  Extension $instance
     * @return void
     */
    public function register(Extension $instance): void
    {
        $this->instances[get_class($instance)] = $instance;
    }

    /**
     * Declare a method (on an extension) as exposed, and thereby
     * accessible by the randomizer.
     *
     * @param  Extension $instance
     * @param  string    $method
     * @return void
     */
    public function expose(Extension $instance, string $method): void
    {
        $this->exposedMethods[$method] = get_class($instance);
    }
}
