<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Exceptions\TimetravelException;
use Dbt\Timeline\Interfaces\SliceInterface;
use Dbt\Timeline\Slice\Stack;

class Slice implements SliceInterface
{
    /** @var string */
    public static $anonymousName = 'anonymous';

    /** @var \Carbon\Carbon */
    private $start;

    /** @var \Carbon\Carbon */
    private $end;

    /** @var string */
    private $name;

    public function __construct (string $name, Carbon $start, Carbon $end)
    {
        if (!$start->isBefore($end)) {
            throw new TimetravelException();
        }

        $this->start = $start;
        $this->end = $end;
        $this->name = $name;
    }

    public static function anonymous (Carbon $start, Carbon $end): self
    {
        return new self(self::$anonymousName, $start, $end);
    }

    public function start (): Carbon
    {
        return $this->start;
    }

    public function end (): Carbon
    {
        return $this->end;
    }

    public function name (): string
    {
        return $this->name;
    }

    /**
     * Is the given start timestamp contained within this slice, not inclusive
     * of the end timestamp? This exclusivity is important when casting
     * slices to nodes, since nodes can be merged when they're exact.
     */
    public function contains (Carbon $start): bool
    {
        if ($start->greaterThanOrEqualTo($this->end)) {
            return false;
        }

        return $start->greaterThanOrEqualTo($this->start)
            && $start->lessThan($this->end);
    }

    /**
     * Does the given slice overlap this slice?
     */
    public function overlaps (SliceInterface $slice): bool
    {
        return Stack::make($this, $slice)->hasOverlaps();
    }
}
