<?php

namespace HP\Modules\Products\Listing\Presenters\Responses;

use HP\Modules\Products\Listing\Presenters\ProductCollectionPresenter;
use HP\Modules\Products\Listing\Responses\Response;

class ResponsePresenter
{
    private $response;
    private $presenter;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function present(): ResponsePresenter
    {
        $this->presenter = [
            'status' => [
                'code' => $this->response->getStatus()->getCode(),
                'message' => $this->response->getStatus()->getMessage(),
            ],
            'products' => (new ProductCollectionPresenter($this->response->getProductCollection()))->present()->toArray()
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
