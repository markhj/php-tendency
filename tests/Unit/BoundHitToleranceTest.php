<?php

declare(strict_types=1);

namespace Tests\Unit;

use Markhj\PhpTendency\RandomFloat;
use PHPUnit\Framework\Attributes\Test;
use Tests\StandardTestCase;

class BoundHitToleranceTest extends StandardTestCase
{
    /**
     * Sometimes the algorithm will hit the bounds of the min/max,
     * and that's completely fine. But if it happens too frequently,
     * we know there's something off with the algorithm.
     *
     * This value defines the number of times during a test we want
     * to tolerate that the bounds are hit.
     */
    private const int BOUND_HIT_TOLERANCE = 4;

    /**
     * All right, so I supposed there's a theoretical possibility this
     * test could fail. But in practice it should suffice to run 50
     * iterations to see if we can trigger the computation to produce
     * a number which isn't between 0 and 1.
     *
     * @return void
     */
    #[Test]
    public function boundHits(): void
    {
        $atBounds = 0;
        $random = new RandomFloat(0, 1, 0.2);

        for ($i = 0; $i < 50; $i++) {
            $result = $random->compute();
            $this->assertGreaterThanOrEqual(0, $result->computed);
            $this->assertLessThanOrEqual(1, $result->computed);

            if ($result->computed === 0.0 || $result->computed === 1.0) {
                $atBounds++;
            }
        }

        $this->assertLessThan(self::BOUND_HIT_TOLERANCE, $atBounds);
    }
}
