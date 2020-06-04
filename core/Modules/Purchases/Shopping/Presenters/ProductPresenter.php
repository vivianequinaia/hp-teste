<?php

namespace HP\Modules\Purchases\Shopping\Presenters;

use HP\Modules\Purchases\Shopping\Entities\Product;

class ProductPresenter
{
    private $product;
    private $presenter = [];

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function present(): productPresenter
    {
        $this->presenter = [
            'id' => $this->product->getId(),
            'name' => $this->product->getName(),
            'amount' => $this->product->getAmount(),
            'qty_stock' => $this->product->getQuantityStock(),
            'last_purchase' => [
                'date' => $this->product->getPurchase()->getLastPurchase(),
                'value' => $this->product->getPurchase()->getValue()
            ]
        ];

        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
