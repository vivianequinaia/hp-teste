<?php

namespace HP\Modules\Products\Listing\Collections;

use HP\Modules\Products\Listing\Entities\Product;

class ProductCollection
{
    private $collector;

    public function add(Product $product): void
    {
        $this->collector[] = $product;
    }

    public function all()
    {
        return $this->collector;
    }
}
