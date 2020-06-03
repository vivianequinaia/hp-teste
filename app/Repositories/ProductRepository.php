<?php

namespace App\Repositories;

use App\Models\Product;
use HP\Modules\Products\Create\Exceptions\CreateProductDatabaseException;
use HP\Modules\Products\Create\Gateways\CreateProductGateway;
use HP\Modules\Products\Create\Requests\Request;

class ProductRepository implements CreateProductGateway
{
    private $model = Product::class;

    public function create(Request $request): void
    {
        try {
            $this->model::create(
                [
                    'name' => $request->getName(),
                    'quantity_stock' => $request->getQuantityStock(),
                    'amount' => $request->getAmount(),
                ]
            );
        } catch (\Exception $exception) {
            throw new CreateProductDatabaseException($exception);
        }
    }
}
