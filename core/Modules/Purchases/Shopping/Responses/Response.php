<?php

namespace HP\Modules\Purchases\Shopping\Responses;

use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Presenters\Responses\ResponsePresenter;

class Response implements ResponseInterface
{
    private $status;
    private $payment;

    public function __construct(Status $status, Payment $payment)
    {
        $this->status = $status;
        $this->payment = $payment;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function getPresenter(): ResponsePresenter
    {
        return (new ResponsePresenter($this))->present();
    }
}
