<?php

namespace HP\Modules\Purchases\Shopping\Rules;

use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Gateways\ShoppingPaymentGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;
use HP\Modules\Purchases\Shopping\Resolvers\PaymentTotalResolver;

class ShoppingPaymentRule
{
    private $shoppingPaymentGateway;
    private $request;
    private $amount;

    public function __construct(ShoppingPaymentGateway $shoppingPaymentGateway, Request $request)
    {
        $this->shoppingPaymentGateway = $shoppingPaymentGateway;
        $this->request = $request;
    }

    public function __invoke(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function apply(): Payment
    {
        $total = (new PaymentTotalResolver($this->request->getQuantityPurchased(), $this->amount))->resolve();
        return $this->shoppingPaymentGateway->payment($this->request, $total);
    }
}
