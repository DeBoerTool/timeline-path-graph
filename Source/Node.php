<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Interfaces\NodeInterface;

class Node implements NodeInterface
{
    /** @var \Carbon\Carbon */
    private $at;

    /** @var string */
    private $name;

    public function __construct (string $name, Carbon $at)
    {
        $this->at = $at;
        $this->name = $name;
    }

    public function name (): string
    {
        return $this->name;
    }

    public function timestamp (): Carbon
    {
        return $this->at;
    }
}
