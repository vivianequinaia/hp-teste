<?php

namespace HP\Modules\Products\Create\Rules;

use HP\Modules\Products\Create\Gateways\CreateProductGateway;
use HP\Modules\Products\Create\Requests\Request;

class CreateProductRule
{
    private $createProductGateway;
    private $request;

    public function __construct(CreateProductGateway $createProductGateway, Request $request)
    {
        $this->createProductGateway = $createProductGateway;
        $this->request = $request;
    }

    public function apply(): void
    {
        $this->createProductGateway->create($this->request);
    }
}
