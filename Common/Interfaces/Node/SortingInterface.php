<?php

namespace Dbt\Timeline\Interfaces\Node;

use Dbt\Timeline\Interfaces\NodeInterface;

interface SortingInterface
{
    /**
     * @return \Dbt\Timeline\Interfaces\NodeInterface[]
     */
    public function sort (NodeInterface ...$nodes): array;
}
