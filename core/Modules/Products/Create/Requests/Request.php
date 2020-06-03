<?php

namespace HP\Modules\Products\Create\Requests;

class Request
{
    private $name;
    private $amount;
    private $quantityStock;

    public function __construct(string $name, float $amount, int $quantityStock)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->quantityStock = $quantityStock;
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
}
