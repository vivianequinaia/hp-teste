<?php

namespace HP\Modules\Products\Show\Presenters\Responses;

use HP\Modules\Products\Show\Presenters\ProductPresenter;
use HP\Modules\Products\Show\Responses\Response;

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
            'product' => (new ProductPresenter($this->response->getProduct()))->present()->toArray()
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
