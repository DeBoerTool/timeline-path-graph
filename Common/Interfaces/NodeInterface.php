<?php

namespace Dbt\Timeline\Interfaces;

use Carbon\Carbon;

interface NodeInterface
{
    public function name (): string;
    public function timestamp (): Carbon;
}
