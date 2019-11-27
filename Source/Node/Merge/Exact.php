<?php

namespace Dbt\Timeline\Node\Merge;

use Dbt\Timeline\Interfaces\Node\MergingInterface;
use Dbt\Timeline\Interfaces\NodeInterface;
use Dbt\Timeline\Node;

class Exact implements MergingInterface
{
    /**
     * @return \Dbt\Timeline\Node[]
     */
    public static function merge (NodeInterface ...$nodes): array
    {
        /** @var NodeInterface $previous */
        $previous = null;
        $iteration = 0;
        $merged = [];

        foreach ($nodes as $current) {
            $iteration += 1;

            /*
             * If on first iteration, move the current item into the buffer and
             * continue.
             */
            if ($iteration === 1) {
                $previous = $current;

                continue;
            }

            /*
             * If the previous node is equal to the current node, merge the
             * current node into the previous node and continue without pushing
             * anything onto the stack.
             */
            if ($previous->timestamp()->equalTo($current->timestamp())) {
                $previous = new Node(
                    $current->name(),
                    $current->timestamp()
                );

                if ($iteration === count($nodes)) {
                    $merged[] = $previous;
                }
            }

            /*
             * Otherwise push the previous node onto the stack, and make the
             * current node the previous node.
             */
            else {
                $merged[] = $previous;

                $previous = $current;

                /*
                 * On the last iteration, push the last node onto the stack.
                 */
                if ($iteration === count($nodes)) {
                    $merged[] = $current;
                }
            }
        }

        return $merged;
    }
}
