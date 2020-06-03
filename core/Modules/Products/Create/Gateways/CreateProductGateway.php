<?php

namespace HP\Modules\Products\Create\Gateways;

use HP\Modules\Products\Create\Requests\Request;

interface CreateProductGateway
{
    public function create(Request $request): void;
}
