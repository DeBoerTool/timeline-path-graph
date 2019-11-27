<?php

namespace Dbt\Timeline\Interfaces\Slice;

use Dbt\Timeline\Interfaces\SliceInterface;

interface SortingInterface
{
    /**
     * @return \Dbt\Timeline\Interfaces\SliceInterface[]
     */
    public static function sort (SliceInterface ...$slices): array;
}
