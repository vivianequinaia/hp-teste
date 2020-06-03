<?php

namespace HP\Modules\Products\Create\Exceptions;

class CreateProductDatabaseException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error while creating product.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
