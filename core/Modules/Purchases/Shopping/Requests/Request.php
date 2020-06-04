<?php

namespace HP\Modules\Purchases\Shopping\Requests;

use HP\Modules\Purchases\Shopping\Entities\CreditCard;

class Request
{
    private $productId;
    private $quantityPurchased;
    private $creditCard;

    public function __construct(int $productId, int $quantityPurchased, CreditCard $creditCard)
    {
        $this->productId = $productId;
        $this->quantityPurchased = $quantityPurchased;
        $this->creditCard = $creditCard;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantityPurchased(): int
    {
        return $this->quantityPurchased;
    }

    public function getCreditCard(): CreditCard
    {
        return $this->creditCard;
    }
}
