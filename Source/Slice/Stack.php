<?php

namespace Dbt\Timeline\Slice;

use Dbt\Timeline\Exceptions\NonContiguousSlicesException;
use Dbt\Timeline\Interfaces\Slice\SortingInterface;
use Dbt\Timeline\Interfaces\SliceInterface;
use Dbt\Timeline\Node;
use Dbt\Timeline\Node\Stack as NodeStack;
use Dbt\Timeline\Slice\Sort\Ascending;

final class Stack
{
    /** @var string */
    public static $sortingClass = Ascending::class;

    /** @var \Dbt\Timeline\Interfaces\SliceInterface[] */
    private $slices;

    public function __construct (SliceInterface ...$slices)
    {
        $this->slices = $this->getSortingStrategy()::sort(...$slices);
    }

    public static function make (SliceInterface ...$slices): self
    {
        return new self(...$slices);
    }

    public function count (): int
    {
        return count($this->slices);
    }

    public function get (int $index): SliceInterface
    {
        return $this->slices[$index];
    }

    private function getSortingStrategy (): SortingInterface
    {
        return new self::$sortingClass;
    }

    /*
     * Because the stack is sorted at instantiation, we only need to check if
     * the current start is contained within the previous slice.
     */
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

    public function nodes (): NodeStack
    {
        if ($this->hasOverlaps()) {
            throw new NonContiguousSlicesException();
        }

        $nodes = new NodeStack();

        foreach ($this->slices as $slice) {
            $nodes->buffer(
                Node::anonymous($slice->start()),
                new Node($slice->name(), $slice->end())
            );
        }

        return $nodes->writeBuffer();
    }
}
