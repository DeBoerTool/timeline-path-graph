<?php

namespace Dbt\Timeline\Collections;

use Dbt\Timeline\Interfaces\NodeInterface;

class NodeCollection
{
    /** @var \Dbt\Timeline\Interfaces\NodeInterface[] */
    private $nodes;

    public function __construct (NodeInterface ...$nodes)
    {
        usort($nodes, function (NodeInterface $one, NodeInterface $two) {
            return $one->timestamp()->isBefore($two->timestamp())
                ? -1
                : 1;
        });

        $this->nodes = $nodes;
    }
}
