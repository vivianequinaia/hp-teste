<?php

namespace HP\Modules\Products\Listing\Exceptions;

class FindProductDatabaseException extends \Exception
{
    public function __construct(
        \Throwable $previous,
        $message = 'Error while find products.',
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);
    }
}
