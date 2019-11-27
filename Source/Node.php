<?php

namespace Dbt\Timeline;

use Carbon\Carbon;
use Dbt\Timeline\Interfaces\NodeInterface;

class Node implements NodeInterface
{
    /** @var string */
    public static $anonymousName = 'anonymous';

    /** @var \Carbon\Carbon */
    private $at;

    /** @var string */
    private $name;

    public function __construct (string $name, Carbon $at)
    {
        $this->at = $at;
        $this->name = $name;
    }

    public static function make (string $name, Carbon $at): self
    {
        return new self($name, $at);
    }

    public static function anonymous (Carbon $at): self
    {
        return new self(self::$anonymousName, $at);
    }

    public function name (): string
    {
        return $this->name;
    }

    public function timestamp (): Carbon
    {
        return $this->at;
    }

    public function isAnonymous (): bool
    {
        return $this->name === self::$anonymousName;
    }
}
