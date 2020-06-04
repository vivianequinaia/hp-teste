<?php

namespace App\Repositories;

use App\Models\Product;
use HP\Modules\Products\Create\Exceptions\CreateProductDatabaseException;
use HP\Modules\Products\Create\Gateways\CreateProductGateway;
use HP\Modules\Products\Create\Requests\Request as CreateRequest;
use HP\Modules\Products\Delete\Exceptions\DeleteProductDatabaseException;
use HP\Modules\Products\Delete\Gateways\DeleteProductGateway;
use HP\Modules\Products\Delete\Requests\Request as DeleteRequest;
use HP\Modules\Products\Listing\Collections\ProductCollection;
use HP\Modules\Products\Listing\Exceptions\FindProductDatabaseException;
use HP\Modules\Products\Listing\Gateways\FindProductGateway;
use HP\Modules\Products\Listing\Entities\Product as ListingProduct;
use HP\Modules\Products\Show\Entities\Purchase;
use HP\Modules\Products\Show\Exceptions\ProductNotFoundException;
use HP\Modules\Products\Show\Gateways\GetProductDataGateway;
use HP\Modules\Products\Show\Requests\Request;
use HP\Modules\Products\Show\Entities\Product as ShowProduct;

class ProductRepository implements CreateProductGateway, DeleteProductGateway, FindProductGateway, GetProductDataGateway
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

    public function findProducts(): ProductCollection
    {
        try {
            $result = $this->model::where('active', true)
                ->where('quantity_stock', '>', 0)
                ->get()
                ->toArray();
        } catch (\Exception $exception) {
            throw new FindProductDatabaseException($exception);
        }

        $productCollection = new ProductCollection();
        foreach ($result as $product) {
            $productCollection->add(
                new ListingProduct(
                    $product['name'],
                    $product['amount'],
                    $product['quantity_stock']
                )
            );
        }

        return $productCollection;
    }

    public function getProduct(Request $request): ShowProduct
    {
        $purchaseModel = \App\Models\Purchase::class;
        try {
            $productResult = $this->model::where('id', $request->getId())
                ->where('active', true)
                ->first();
        } catch (\Exception $exception) {
            throw new FindProductDatabaseException($exception);
        }

        if(is_null($productResult)) {
            throw (new ProductNotFoundException())->setProductId($request->getId());
        }

        try {
            $purchaseResult = $purchaseModel::where('product_id', $productResult['id'])
                ->orderBy('id', 'desc')
                ->first();
        } catch (\Exception $exception) {
            throw new FindProductDatabaseException($exception);
        }

        return new ShowProduct(
            $productResult['id'],
            $productResult['name'],
            $productResult['amount'],
            $productResult['quantity_stock'],
            new Purchase(
                isset($purchaseResult['created_at']) ?
                    date('Y-m-d', strtotime($purchaseResult['created_at'])) :
                    null,
                $purchaseResult['total'] ?? null
            )
        );
    }
}
