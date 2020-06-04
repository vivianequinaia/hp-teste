<?php

namespace HP\Modules\Products\Listing\Gateways;

use HP\Modules\Products\Listing\Collections\ProductCollection;

interface FindProductGateway
{
    public function findProducts(): ProductCollection;
}
