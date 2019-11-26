<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Collections\SliceCollection;

class Path
{
    /** @var \Carbon\Carbon */
    private $start;

    /** @var \Carbon\Carbon */
    private $end;

    /** @var \Dbt\Timeline\Collections\SliceCollection */
    private $slices;

    public function __construct (
        Carbon $start,
        Carbon $end,
        SliceCollection $slices
    )
    {
        $this->start = $start;
        $this->end = $end;
        $this->slices = $slices;
    }
}
