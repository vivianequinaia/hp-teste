<?php

namespace HP\Modules\Products\Delete\Rules;

use HP\Modules\Products\Delete\Gateways\DeleteProductGateway;
use HP\Modules\Products\Delete\Requests\Request;

class DeleteProductRule
{
    private $deleteProductGateway;
    private $request;

    public function __construct(DeleteProductGateway $deleteProductGateway, Request $request)
    {
        $this->deleteProductGateway = $deleteProductGateway;
        $this->request = $request;
    }

    public function apply(): void
    {
        $this->deleteProductGateway->delete($this->request);
    }
}
