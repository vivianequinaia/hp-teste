<?php

namespace HP\Modules\Products\Listing\Responses;

use HP\Modules\Products\Listing\Collections\ProductCollection;
use HP\Modules\Products\Listing\Presenters\Responses\ResponsePresenter;

class Response implements ResponseInterface
{
    private $status;
    private $productCollection;

    public function __construct(Status $status, ProductCollection $productCollection)
    {
        $this->status = $status;
        $this->productCollection = $productCollection;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getProductCollection(): ProductCollection
    {
        return $this->productCollection;
    }

    public function getPresenter(): ResponsePresenter
    {
        return (new ResponsePresenter($this))->present();
    }
}
