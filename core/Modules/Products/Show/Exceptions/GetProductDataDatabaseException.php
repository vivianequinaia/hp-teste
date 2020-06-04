<?php

namespace HP\Modules\Products\Show\Exceptions;

class GetProductDataDatabaseException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error while get product data.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
