<?php

namespace HP\Modules\Products\Create\Presenters\Responses;

use HP\Modules\Products\Create\Responses\Response;

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
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
