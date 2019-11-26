<?php

namespace Dbt\Timeline\Exceptions;

use Exception;
use Throwable;

class TimetravelException extends Exception
{
    public function __construct (
        $message = 'Start timestamps must be before end timestamps.',
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
