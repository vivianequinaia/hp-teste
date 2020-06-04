<?php

namespace HP\Modules\Purchases\Shopping\Resolvers;

class PaymentTotalResolver
{
    private $quantity;
    private $amount;

    public function __construct(int $quantity, float $amount)
    {
        $this->quantity = $quantity;
        $this->amount = $amount;
    }

    public function resolve(): float
    {
        return $this->quantity * $this->amount;
    }
}
