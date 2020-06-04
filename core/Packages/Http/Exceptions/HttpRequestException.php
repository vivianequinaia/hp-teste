<?php

namespace HP\Packages\Http\Exceptions;

use Throwable;

class HttpRequestException extends \Exception
{
    public function __construct(
        $code = 500,
        $message = "Failure finish the puchase.",
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
