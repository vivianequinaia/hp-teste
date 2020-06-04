<?php

namespace HP\Modules\Products\Listing\Presenters;

use HP\Modules\Products\Listing\Collections\ProductCollection;

class ProductCollectionPresenter
{
    private $productCollection;
    private $presenter = [];

    public function __construct(ProductCollection $productCollection)
    {
        $this->productCollection = $productCollection;
    }

    public function present(): productCollectionPresenter
    {
        if(!empty($this->productCollection->all())) {
            foreach ($this->productCollection->all() as $product) {
                array_push($this->presenter, [
                        'name' => $product->getName(),
                        'amount' => $product->getAmount(),
                        'qty_stock' => $product->getQuantityStock(),
                    ]
                );
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        return $this->presenter;
    }
}
