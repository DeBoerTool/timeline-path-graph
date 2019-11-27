<?php

namespace Dbt\Timeline\Slice\Sort;

use Dbt\Timeline\Interfaces\Slice\SortingInterface;
use Dbt\Timeline\Interfaces\SliceInterface;

/**
 * Sort slices by their start timestamp, ascending.
 */
class Ascending implements SortingInterface
{
    public static function sort (SliceInterface ...$slices): array
    {
        usort($slices, function (SliceInterface $one, SliceInterface $two) {
            if ($one->start()->equalTo($two->start())) {
                return $one->end()->isAfter($two->end()) ? -1 : 1;
            }

            return $one->start()->isBefore($two->start())
                ? -1
                : 1;
        });

        return $slices;
    }
}
