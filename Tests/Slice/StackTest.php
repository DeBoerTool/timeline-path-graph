<?php

namespace Dbt\Timeline\Tests\Slice;

use Dbt\Timeline\Exceptions\NonContiguousSlicesException;
use Dbt\Timeline\Node;
use Dbt\Timeline\Slice;
use Dbt\Timeline\Slice\Stack;
use Dbt\Timeline\Tests\UnitTestCase;

class StackTest extends UnitTestCase
{
    /** @test */
    public function checking_for_overlaps (): void
    {
        $slice1 = Slice::anonymous($this->ts(), $this->ts(1));
        $slice2 = Slice::anonymous($this->ts(2), $this->ts(3));
        $slice3 = Slice::anonymous($this->ts(), $this->ts(4));

        $col1 = Stack::make($slice1, $slice2);

        $this->assertFalse($col1->hasOverlaps());

        $col2 = Stack::make($slice1, $slice3);

        $this->assertTrue($col2->hasOverlaps());
    }

    /** @test */
    public function failing_to_get_nodes (): void
    {
        $this->expectException(NonContiguousSlicesException::class);

        /*
         * Since slice 2 overlaps slice 1, we can't translate these slices into
         * a contiguous path graph. The data should be serial, not parallel.
         */
        $slice1 = Slice::anonymous($this->ts(), $this->ts(1));
        $slice2 = Slice::anonymous($this->ts(), $this->ts(3));

        Stack::make($slice1, $slice2)->nodes();
    }

    /** @test */
    public function getting_nodes_for_non_contiguous_non_overlapping_slices (): void
    {
        $name1 = $this->rs(8);
        $name2 = $this->rs(8);

        $slice1 = new Slice($name1, $this->ts(), $this->ts(1));
        $slice2 = new Slice($name2, $this->ts(4), $this->ts(6));

        $nodes = Stack::make($slice1, $slice2)->nodes();

        $this->assertSame(4, $nodes->count());

        /*
         * Even iterations (2, 4, 6) should be named, and odd iteration
         * anonymous, assuming that the input slices are both non-overlapping
         * and non contiguous.
         *
         * Transforming these back into slices will result in two anonymous
         * slices representing the unallocated (anonymous) time.
         */
        $nodes->each(function (Node $node, int $_, int $loop) {
            if ($loop % 2 === 0) {
                $this->assertFalse($node->isAnonymous());
            } else {
                $this->assertTrue($node->isAnonymous());
            }
        });
    }

    /** @test */
    public function getting_nodes_for_contiguous_non_overlapping_slices (): void
    {
        $name1 = $this->rs(8);
        $name2 = $this->rs(8);

        $slice1 = new Slice($name1, $this->ts(), $this->ts(1));
        $slice2 = new Slice($name2, $this->ts(1), $this->ts(6));

        $nodes = Stack::make($slice1, $slice2)->nodes();

        /*
         * 3 nodes instead of 4, since the identically timestamped nodes will
         * be merged.
         */
        $this->assertSame(3, $nodes->count());

        /*
         * When nodes are merged, there will always be a named end node being
         * merged with an anonymous start node. The named node has precedence
         * since, when casting nodes to normalized slices, the slice inherits
         * its end node's name. This ensures named nodes are not overwritten.
         */
        $this->assertSame('anonymous', $nodes->get(0)->name());
        $this->assertSame($name1, $nodes->get(1)->name());
        $this->assertSame($name2, $nodes->get(2)->name());
    }
}
