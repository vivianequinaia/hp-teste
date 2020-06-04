<?php

namespace HP\Modules\Purchases\Shopping\Exceptions;

class InvalidCreditCardException extends \Exception
{
    public function __construct(
        \Throwable $previous = null,
        $message = 'Invalid credit card.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
