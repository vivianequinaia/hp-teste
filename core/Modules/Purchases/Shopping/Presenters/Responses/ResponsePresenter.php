<?php

namespace HP\Modules\Purchases\Shopping\Presenters\Responses;

use HP\Modules\Purchases\Shopping\Presenters\ProductPresenter;
use HP\Modules\Purchases\Shopping\Responses\Response;

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
            'message' => $this->response->getPayment()->getStatus()
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
