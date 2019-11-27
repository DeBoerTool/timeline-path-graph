<?php

namespace Dbt\Timeline\Node\Sort;

use Dbt\Timeline\Interfaces\Node\SortingInterface;
use Dbt\Timeline\Interfaces\NodeInterface;

class Ascending implements SortingInterface
{
    public function sort (NodeInterface ...$nodes): array
    {
        usort($nodes, function (NodeInterface $one, NodeInterface $two) {
            return $one->timestamp()->isBefore($two->timestamp())
                ? -1
                : 1;
        });

        return $nodes;
    }
}
