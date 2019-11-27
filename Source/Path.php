<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Slice\Stack;

class Path
{
    /** @var \Carbon\Carbon */
    private $start;

    /** @var \Carbon\Carbon */
    private $end;

    /** @var \Dbt\Timeline\Slice\Stack */
    private $slices;

    public function __construct (
        Carbon $start,
        Carbon $end,
        Stack $slices
    )
    {
        $this->start = $start;
        $this->end = $end;

        $this->slices = $slices->truncate($start, $end);
    }
}
