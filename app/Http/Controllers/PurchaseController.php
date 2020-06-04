<?php

namespace App\Http\Controllers;

use App\Adapters\LogMonologAdapter;
use App\Http\Requests\Purchase\Request;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PurchaseRepository;
use HP\Modules\Purchases\Shopping\Factories\RequestFactory;
use HP\Modules\Purchases\Shopping\UseCase;

class PurchaseController
{
    public function shopping(Request $httpRequest)
    {
        $card = $httpRequest->get('card');
        $request = (new RequestFactory())->create(
            $httpRequest->get('product_id'),
            $httpRequest->get('quantity_purchased'),
            $card['owner'],
            $card['card_number'],
            $card['date_expiration'],
            $card['flag'],
            $card['cvv']
        );

        $useCase = new UseCase(
            new ProductRepository(),
            new PaymentGatewayRepository(),
            new PurchaseRepository(),
            new LogMonologAdapter()
        );

        $useCase->execute($request);

        return response()->json(
            $useCase->getResponse()->getPresenter()->toArray(),
            $useCase->getResponse()->getStatus()->getCode()
        );
    }
}
