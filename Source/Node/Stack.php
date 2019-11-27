<?php

namespace Dbt\Timeline\Node;

use Carbon\Carbon;
use Closure;
use Dbt\Timeline\Exceptions\TimetravelException;
use Dbt\Timeline\Interfaces\Node\MergingInterface;
use Dbt\Timeline\Interfaces\Node\SortingInterface;
use Dbt\Timeline\Interfaces\NodeInterface;
use Dbt\Timeline\Node;
use Dbt\Timeline\Node\Merge\Exact;
use Dbt\Timeline\Node\Sort\Ascending;
use Dbt\Timeline\Slice;
use Dbt\Timeline\Slice\Stack as SliceStack;

/**
 * An immutable collection of nodes. To add nodes, `buffer(...)` them. Then
 * `writeBuffer()` which will give you a new collection.
 */
final class Stack
{
    /** @var string */
    public static $sortingStrategy = Ascending::class;

    /** @var string */
    public static $mergingStrategy = Exact::class;

    /** @var \Dbt\Timeline\Interfaces\NodeInterface[] */
    private $nodes;

    /** @var \Dbt\Timeline\Interfaces\NodeInterface[] */
    private $buffer;

    public function __construct (NodeInterface ...$nodes)
    {
        $this->buffer = [];

        $sorted = $this->getSortingStrategy()
            ->sort(...$nodes);

        $this->nodes = $this->getMergingStrategy()
            ->merge(...$sorted);
    }

    public function setRoot (Carbon $start): Stack
    {
        $nodes = [
            Node::anonymous($start)
        ];

        foreach ($this->nodes as $node) {
            if ($node->timestamp()->greaterThan($start)) {
                $nodes[] = $node;
            }
        }

        return new self(...$nodes);
    }

    public function getRoot (): Node
    {
        return $this->nodes[0];
    }

    public function setLeaf (Carbon $start): Stack
    {
        if ($start->lessThanOrEqualTo($this->getRoot()->timestamp())) {
            throw new TimetravelException();
        }

        /** @var \Dbt\Timeline\Node $previous */
        $previous = null;
        $nodes = [];

        foreach ($this->nodes as $index => $current) {
            $previous = $current;

            if ($index === 0) {
                continue;
            }
        }
    }

    public function count (): int
    {
        return count($this->nodes);
    }

    public function each (Closure $callback): void
    {
        foreach ($this->nodes as $index => $node) {
            if (($callback($node, $index, $index + 1) ?? true) === false) {
                break;
            }
        }
    }

    public function get (int $index): NodeInterface
    {
        return $this->nodes[$index];
    }

    /**
     * Buffer unsorted and unmerged nodes since sorting is expensive.
     */
    public function buffer (NodeInterface ...$nodes): void
    {
        $this->buffer = array_merge($this->buffer, $nodes);
    }

    /**
     * Write the buffer nodes and preexisting nodes to a new stack.
     */
    public function writeBuffer (): self
    {
        return new self(
            ...$this->nodes,
            ...$this->buffer
        );
    }

    public function slices (): SliceStack
    {
        /** @var \Dbt\Timeline\Node $previous */
        $previous = null;
        $slices = [];
        $iteration = 0;

        foreach ($this->nodes as $current) {
            $iteration += 1;

            if ($iteration === 1) {
                $previous = $current;
                continue;
            }

            $slices[] = new Slice(
                $current->name(),
                $previous->timestamp(),
                $current->timestamp()->subMillisecond()
            );

            $previous = $current;
        }

        return new SliceStack(...$slices);
    }

    private function getSortingStrategy (): SortingInterface
    {
        return new self::$sortingStrategy;
    }

    private function getMergingStrategy (): MergingInterface
    {
        return new self::$mergingStrategy;
    }
}
