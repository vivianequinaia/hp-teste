<?php

namespace HP\Modules\Products\Show\Rules;

use HP\Modules\Products\Show\Entities\Product;
use HP\Modules\Products\Show\Gateways\GetProductDataGateway;
use HP\Modules\Products\Show\Requests\Request;

class GetProductDataRule
{
    private $getProductDataGateway;
    private $request;

    public function __construct(GetProductDataGateway $getProductDataGateway, Request $request)
    {
        $this->getProductDataGateway = $getProductDataGateway;
        $this->request = $request;
    }

    public function apply(): Product
    {
        return $this->getProductDataGateway->getProduct($this->request);
    }
}
