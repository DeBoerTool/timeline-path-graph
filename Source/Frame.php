<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Slice\Stack;

/**
 * A frame is essentially a container for a stack of slices. The given slices
 * will be truncated by the start and end timestamps with the end goal of
 * producing a contiguous path graph.
 */
class Frame
{
    /** @var \Carbon\Carbon */
    private $start;

    /** @var \Carbon\Carbon */
    private $end;

    /** @var \Dbt\Timeline\Slice\Stack */
    private $slices;

    public function __construct (Carbon $start, Carbon $end, Stack $slices)
    {
        $this->start = $start;
        $this->end = $end;
        $this->slices = $slices;
    }

    public function get (): Stack
    {
        $nodes = $this->slices->nodes();

        $withRoot = $nodes->setRoot($this->start);
        $withLeaf = $nodes->setLeaf($this->end);

        return $withLeaf->slices();
    }
}
