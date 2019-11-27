<?php

namespace Dbt\Timeline\Exceptions;

use Exception;
use Throwable;

class NonContiguousSlicesException extends Exception
{
    public function __construct (
        $message = 'Non-contiguous slices cannot be transformed into a path graph.',
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
