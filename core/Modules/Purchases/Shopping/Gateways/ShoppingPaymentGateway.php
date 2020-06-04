<?php

namespace HP\Modules\Purchases\Shopping\Gateways;

use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Requests\Request;

interface ShoppingPaymentGateway
{
    public function payment(Request $request, float $total): Payment;
}
