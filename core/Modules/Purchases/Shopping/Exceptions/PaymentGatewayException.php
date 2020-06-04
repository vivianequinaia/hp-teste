<?php

namespace HP\Modules\Purchases\Shopping\Exceptions;

class PaymentGatewayException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error when trying to connect to the payment gateway',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
