<?php

namespace HP\Modules\Products\Create\Responses;

use HP\Modules\Products\Create\Presenters\Responses\ResponsePresenter;

class Response implements ResponseInterface
{
    private $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getPresenter(): ResponsePresenter
    {
        return (new ResponsePresenter($this))->present();
    }
}
