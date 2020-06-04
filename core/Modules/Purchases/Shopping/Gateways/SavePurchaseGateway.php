<?php

namespace HP\Modules\Purchases\Shopping\Gateways;

use HP\Modules\Purchases\Shopping\Requests\Request;

interface SavePurchaseGateway
{
    public function save(Request $request, float $amount): void;
}
