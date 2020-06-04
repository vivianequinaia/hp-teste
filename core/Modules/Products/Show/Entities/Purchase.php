<?php

namespace HP\Modules\Products\Show\Entities;

class Purchase
{
    private $lastPurchase;
    private $value;

    public function __construct(?string $lastPurchase, ?float $value)
    {
        $this->lastPurchase = $lastPurchase;
        $this->value = $value;
    }

    public function getLastPurchase(): ?string
    {
        return $this->lastPurchase;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }
}
