<?php

namespace HP\Modules\Products\Show\Exceptions;

class ProductNotFoundException extends \Exception
{
    private $productId;

    public function __construct(
        \Throwable $previous = null,
        $message = 'This product does not exist.',
        $code = 404
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): ProductNotFoundException
    {
        $this->productId = $productId;
        return $this;
    }

}
