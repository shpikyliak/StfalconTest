<?php

namespace App\Exception;
use RuntimeException;
class ApiException extends RuntimeException
{
    /**
     * @param  string  $message
     */
    public function __construct(string $message = "API error")
    {
        parent::__construct($message);
    }
}
