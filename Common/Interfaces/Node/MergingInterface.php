<?php

namespace Dbt\Timeline\Interfaces\Node;

use Dbt\Timeline\Interfaces\NodeInterface;

interface MergingInterface
{
    /**
     * @return \Dbt\Timeline\Interfaces\NodeInterface[]
     */
    public static function merge (NodeInterface ...$nodes): array;
}
