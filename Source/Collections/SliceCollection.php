<?php

namespace Dbt\Timeline\Collections;

use Dbt\Timeline\Interfaces\SliceInterface;

class SliceCollection
{
    /** @var \Dbt\Timeline\Interfaces\SliceInterface[] */
    private $slices;

    public function __construct (SliceInterface ...$slices)
    {
        usort($slices, function (SliceInterface $one, SliceInterface $two) {
            return $one->start()->isBefore($two->start())
                ? -1
                : 1;
        });

        $this->slices = $slices;
    }

    /**
     * @return \Dbt\Timeline\Interfaces\SliceInterface[]
     */
    public function all (): array
    {
        return $this->slices;
    }

    /**
     * @param \Dbt\Timeline\Interfaces\SliceInterface|\Dbt\Timeline\Interfaces\SliceInterface[] $slices
     */
    public static function make ($slices): self
    {
        $wrapped = is_array($slices) ? $slices : [$slices];

        return new self(...$wrapped);
    }

    public function hasOverlaps (): bool
    {
        /** @var \Dbt\Timeline\Interfaces\SliceInterface $previous */
        $previous = null;

        foreach ($this->slices as $slice) {
            if ($previous && $previous->contains($slice->start())) {
                return true;
            }

            $previous = $slice;
        }

        return false;
    }
}
