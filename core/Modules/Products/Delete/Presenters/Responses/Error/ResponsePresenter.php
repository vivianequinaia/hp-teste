<?php

namespace HP\Modules\Products\Delete\Presenters\Responses\Error;

use HP\Modules\Products\Delete\Responses\Error\Response;

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
            'error' => $this->response->getError()
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
