<?php

namespace App\Repositories;

use App\Models\Purchase;
use HP\Modules\Purchases\Shopping\Exceptions\SavePurchaseDatabaseException;
use HP\Modules\Purchases\Shopping\Gateways\SavePurchaseGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;

class PurchaseRepository implements SavePurchaseGateway
{
    private $model = Purchase::class;

    public function save(Request $request, float $amount): void
    {
        try {
            $this->model::create(
                [
                    'total' => $amount,
                    'quantity' => $request->getQuantityPurchased(),
                    'product_id' => $request->getProductId()
                ]
            );
        } catch (\Exception $exception) {
            throw new SavePurchaseDatabaseException($exception);
        }
    }
}
