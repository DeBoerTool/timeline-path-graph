<?php

namespace Dbt\Timeline\Tests\Collections;

use Dbt\Timeline\Collections\SliceCollection;
use Dbt\Timeline\Slice;
use Dbt\Timeline\Tests\UnitTestCase;

class SliceCollectionTest extends UnitTestCase
{
    /** @test */
    public function checking_for_overlaps (): void
    {
        $slice1 = new Slice(
            $this->now(),
            $this->now()->addMinutes(1)
        );

        $slice2 = new Slice(
            $this->now()->addMinutes(2),
            $this->now()->addMinutes(3)
        );

        $slice3 = new Slice(
            $this->now(),
            $this->now()->addMinutes(4)
        );

        $col1 = SliceCollection::make([$slice1, $slice2]);

        $this->assertFalse($col1->hasOverlaps());

        $col2 = SliceCollection::make([$slice1, $slice3]);

        $this->assertTrue($col2->hasOverlaps());
    }
}
