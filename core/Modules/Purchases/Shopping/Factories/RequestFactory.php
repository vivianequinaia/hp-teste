<?php

namespace HP\Modules\Purchases\Shopping\Factories;

use HP\Modules\Purchases\Shopping\Entities\CreditCard;
use HP\Modules\Purchases\Shopping\Requests\Request;

class RequestFactory
{
    public function create(
        int $productId,
        int $quantityPurchased,
        string $owner,
        string $cardNumber,
        string $dateExpiration,
        string $flag,
        string $cvv
    ): Request {
        return new Request(
            $productId,
            $quantityPurchased,
            new CreditCard(
                $owner,
                $cardNumber,
                $dateExpiration,
                $flag,
                $cvv
            )
        );
    }
}
