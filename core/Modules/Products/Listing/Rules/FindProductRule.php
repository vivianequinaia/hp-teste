<?php

namespace HP\Modules\Products\Listing\Rules;

use HP\Modules\Products\Listing\Collections\ProductCollection;
use HP\Modules\Products\Listing\Gateways\FindProductGateway;

class FindProductRule
{
    private $findProductGateway;

    public function __construct(FindProductGateway $findProductGateway)
    {
        $this->findProductGateway = $findProductGateway;
    }

    public function apply(): ProductCollection
    {
        return $this->findProductGateway->findProducts();
    }
}
