<?php

namespace App\Adapters\Exceptions;

class NullConfigException extends \Exception
{
    public function __construct(
        $message = "",
        \Throwable $previous = null,
        $code = 400
    ) {
        parent::__construct($message, $code, $previous);
    }
}
