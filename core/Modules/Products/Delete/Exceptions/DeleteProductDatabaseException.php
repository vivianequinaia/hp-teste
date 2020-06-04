<?php

namespace HP\Modules\Products\Delete\Exceptions;

class DeleteProductDatabaseException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error while deleting product.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
