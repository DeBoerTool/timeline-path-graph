<?php

namespace Dbt\Timeline\Interfaces;

use Carbon\Carbon;

interface SliceInterface
{
    public function contains (Carbon $timestamp): bool;
    public function overlaps (SliceInterface $slice): bool;
    public function start (): Carbon;
    public function end (): Carbon;
}
