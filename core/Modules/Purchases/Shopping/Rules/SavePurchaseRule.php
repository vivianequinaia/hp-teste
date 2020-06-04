<?php

namespace HP\Modules\Purchases\Shopping\Rules;

use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Gateways\SavePurchaseGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;
use HP\Modules\Purchases\Shopping\Resolvers\PaymentTotalResolver;

class SavePurchaseRule
{
    private $savePurchaseGateway;
    private $request;
    private $amount;
    private $payment;

    public function __construct(SavePurchaseGateway $savePurchaseGateway, Request $request)
    {
        $this->savePurchaseGateway = $savePurchaseGateway;
        $this->request = $request;
    }

    public function __invoke(float $amount, Payment $payment)
    {
        $this->amount = $amount;
        $this->payment = $payment;
        return $this;
    }

    public function apply(): void
    {
        if ($this->payment->getStatus() == 'Aprovado') {
            $total = (new PaymentTotalResolver($this->request->getQuantityPurchased(), $this->amount))->resolve();
            $this->savePurchaseGateway->save($this->request, $total);
        }
    }
}
