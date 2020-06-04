<?php

namespace HP\Modules\Products\Show\Entities;

class Product
{
    private $id;
    private $name;
    private $amount;
    private $quantityStock;
    private $purchase;

    public function __construct(int $id, string $name, float $amount, int $quantityStock, Purchase $purchase)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->quantityStock = $quantityStock;
        $this->purchase = $purchase;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getQuantityStock(): int
    {
        return $this->quantityStock;
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }
}
