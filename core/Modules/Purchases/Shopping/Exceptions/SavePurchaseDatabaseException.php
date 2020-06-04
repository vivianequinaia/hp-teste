<?php

namespace HP\Modules\Purchases\Shopping\Exceptions;

class SavePurchaseDatabaseException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error while save purchase.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
