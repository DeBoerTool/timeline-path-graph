<?php

namespace Dbt\Timeline\Tests;

use Dbt\Timeline\Exceptions\TimetravelException;
use Dbt\Timeline\Slice;

class SliceTest extends UnitTestCase
{
    /** @test */
    public function instantiating (): void
    {
        $this->expectException(TimetravelException::class);

        $slice = new Slice($this->now(), $this->now()->addMinute());

        $this->assertNotNull($slice);

        /*
         * This will fail since the start and the end are equal when the end
         * should be greater than the start.
         */
        new Slice($this->now(), $this->now());
    }

    /** @test */
    public function checking_if_a_slice_contains_a_timestamp (): void
    {
        $slice = new Slice($this->now(), $this->now()->addMinute());
        $timestamp = $this->now()->addHour();

        $this->assertFalse($slice->contains($timestamp));

        $slice = new Slice($this->now()->subHour(), $this->now()->subMinute());

        $this->assertFalse($slice->contains($timestamp));

        $slice = new Slice($this->now(), $this->now()->addHours(2));

        $this->assertTrue($slice->contains($timestamp));
    }

    /** @test */
    public function checking_if_a_slice_overlaps_another_slice (): void
    {
        $slice1 = new Slice(
            $this->now(),
            $this->now()->addMinutes(1)
        );

        $slice2 = new Slice(
            $this->now()->addMinutes(2),
            $this->now()->addMinutes(3)
        );

        $this->assertFalse($slice1->overlaps($slice2));

        $slice3 = new Slice(
            $this->now()->subMinutes(2),
            $this->now()->addMinutes(3)
        );

        $this->assertTrue($slice1->overlaps($slice3));
    }
}
