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

        $slice = Slice::anonymous($this->ts(), $this->ts()->addMinute());

        $this->assertNotNull($slice);

        /*
         * This will fail since the start and the end are equal when the end
         * should be greater than the start.
         */
        Slice::anonymous($this->ts(), $this->ts());
    }

    /** @test */
    public function checking_if_a_slice_contains_a_timestamp (): void
    {
        $slice = Slice::anonymous($this->ts(), $this->ts()->addMinute());
        $timestamp = $this->ts()->addHour();

        $this->assertFalse($slice->contains($timestamp));

        $slice = Slice::anonymous($this->ts()->subHour(), $this->ts()->subMinute());

        $this->assertFalse($slice->contains($timestamp));

        $slice = Slice::anonymous($this->ts(), $this->ts()->addHours(2));

        $this->assertTrue($slice->contains($timestamp));
    }

    /** @test */
    public function checking_if_a_slice_overlaps_another_slice (): void
    {
        $slice1 = Slice::anonymous(
            $this->ts(),
            $this->ts()->addMinutes(1)
        );

        $slice2 = Slice::anonymous(
            $this->ts()->addMinutes(2),
            $this->ts()->addMinutes(3)
        );

        $this->assertFalse($slice1->overlaps($slice2));

        $slice3 = Slice::anonymous(
            $this->ts()->subMinutes(2),
            $this->ts()->addMinutes(3)
        );

        $this->assertTrue($slice1->overlaps($slice3));
    }
}
