<?php

namespace App\Repositories;

use App\Models\Product;
use HP\Modules\Products\Create\Exceptions\CreateProductDatabaseException;
use HP\Modules\Products\Create\Gateways\CreateProductGateway;
use HP\Modules\Products\Create\Requests\Request as CreateRequest;
use HP\Modules\Products\Delete\Exceptions\DeleteProductDatabaseException;
use HP\Modules\Products\Delete\Gateways\DeleteProductGateway;
use HP\Modules\Products\Delete\Requests\Request as DeleteRequest;

class ProductRepository implements CreateProductGateway, DeleteProductGateway
{
    private $model = Product::class;

    public function create(CreateRequest $request): void
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

    public function delete(DeleteRequest $request): void
    {
        try {
            $this->model::where('id', $request->getId())
                ->update(
                    [
                        'active' => false
                    ]
                );
        } catch (\Exception $exception) {
            throw new DeleteProductDatabaseException($exception);
        }
    }
}
