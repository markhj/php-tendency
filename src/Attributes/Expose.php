<?php

declare(strict_types=1);

namespace Markhj\PhpTendency\Attributes;

use Attribute;

/**
 * This attribute marks methods in extensions which should be exposed
 * through the randomizer's interface.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Expose
{

}
