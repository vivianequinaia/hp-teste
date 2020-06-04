<?php

namespace HP\Modules\Purchases\Shopping\Exceptions;

class GetProductAmountDatabaseException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error while find product amount.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
