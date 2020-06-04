<?php

namespace HP\Modules\Products\Delete\Responses;

use HP\Modules\Products\Delete\Presenters\Responses\ResponsePresenter;

class Response implements ResponseInterface
{
    private $status;
    private $message;

    public function __construct(Status $status, string $message)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getPresenter(): ResponsePresenter
    {
        return (new ResponsePresenter($this))->present();
    }
}
