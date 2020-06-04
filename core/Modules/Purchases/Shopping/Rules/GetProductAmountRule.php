<?php

namespace HP\Modules\Purchases\Shopping\Rules;

use HP\Modules\Purchases\Shopping\Gateways\GetProductAmountGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;

class GetProductAmountRule
{
    private $getProductAmountGateway;
    private $request;

    public function __construct(GetProductAmountGateway $getProductAmountGateway, Request $request)
    {
        $this->getProductAmountGateway = $getProductAmountGateway;
        $this->request = $request;
    }

    public function apply(): float
    {
        return $this->getProductAmountGateway->getAmountById($this->request->getProductId());
    }
}
