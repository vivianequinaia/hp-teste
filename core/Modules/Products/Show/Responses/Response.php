<?php

namespace HP\Modules\Products\Show\Responses;

use HP\Modules\Products\Show\Entities\Product;
use HP\Modules\Products\Show\Presenters\Responses\ResponsePresenter;

class Response implements ResponseInterface
{
    private $status;
    private $product;

    public function __construct(Status $status, Product $product)
    {
        $this->status = $status;
        $this->product = $product;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getProduct(): product
    {
        return $this->product;
    }

    public function getPresenter(): ResponsePresenter
    {
        return (new ResponsePresenter($this))->present();
    }
}
