<?php

namespace Dbt\Timeline\Tests\Slice\Sort;

use Dbt\Timeline\Slice;
use Dbt\Timeline\Tests\UnitTestCase;

class AscendingTest extends UnitTestCase
{
    /** @test */
    public function sorting_by_start_timestamp (): void
    {
        $slice1 = Slice::anonymous($this->ts(), $this->ts(1));
        $slice2 = Slice::anonymous($this->ts(-1), $this->ts(2));

        $sorted = (new Slice\Sort\Ascending())->sort($slice1, $slice2);

        $this->assertSame($slice2, $sorted[0]);
        $this->assertSame($slice1, $sorted[1]);
    }

    /** @test */
    public function sorting_by_longest_if_starts_are_the_same (): void
    {
        $slice1 = Slice::anonymous($this->ts(), $this->ts(10));
        $slice2 = Slice::anonymous($this->ts(), $this->ts(2));
        $slice3 = Slice::anonymous($this->ts(), $this->ts(90));

        $sorted = (new Slice\Sort\Ascending())->sort($slice1, $slice2, $slice3);

        $this->assertSame($slice3, $sorted[0]);
        $this->assertSame($slice1, $sorted[1]);
        $this->assertSame($slice2, $sorted[2]);
    }
}
