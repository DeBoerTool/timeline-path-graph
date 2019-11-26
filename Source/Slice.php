<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Collections\SliceCollection;
use Dbt\Timeline\Exceptions\TimetravelException;
use Dbt\Timeline\Interfaces\SliceInterface;

class Slice implements SliceInterface
{
    /** @var \Carbon\Carbon */
    private $start;

    /** @var \Carbon\Carbon */
    private $end;

    public function __construct (Carbon $start, Carbon $end)
    {
        if (!$start->isBefore($end)) {
            throw new TimetravelException();
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function start (): Carbon
    {
        return $this->start;
    }

    public function end (): Carbon
    {
        return $this->end;
    }

    /**
     * Is the given timestamp contained within this slice (inclusive)?
     */
    public function contains (Carbon $timestamp): bool
    {
        return $timestamp->greaterThanOrEqualTo($this->start)
            && $timestamp->lessThanOrEqualTo($this->end);
    }

    /**
     * Does the given slice overlap this slice?
     */
    public function overlaps (SliceInterface $slice): bool
    {
        return SliceCollection::make([$this, $slice])->hasOverlaps();
    }
}
