<?php

namespace HP\Modules\Products\Show\Gateways;

use HP\Modules\Products\Show\Entities\Product;
use HP\Modules\Products\Show\Requests\Request;

interface GetProductDataGateway
{
    public function getProduct(Request $id): Product;
}
