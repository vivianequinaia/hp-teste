<?php

namespace HP\Packages\Http\Exceptions;

class RequestAttributeNotSetException extends \Exception
{
    public function __construct(string $message , int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
