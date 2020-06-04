<?php

namespace App\Repositories;

use App\Adapters\ConfigAdapter;
use App\Adapters\HttpAdapter;
use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Exceptions\PaymentGatewayException;
use HP\Modules\Purchases\Shopping\Gateways\ShoppingPaymentGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;

class PaymentGatewayRepository implements ShoppingPaymentGateway
{
    private $httpAdapter;
    private $configAdapter;

    public function __construct()
    {
        $this->httpAdapter = new HttpAdapter();
        $this->configAdapter = new ConfigAdapter();
    }

    public function payment(Request $request, float $total): Payment
    {
        try {
            $response = $this->httpAdapter->post(
                $this->configAdapter->getPaymentGatewayUrl(),
                [
                    \GuzzleHttp\RequestOptions::JSON =>
                        [
                            "amount" => $total,
                            "card" => [
                                "owner" => $request->getCreditCard()->getOwner(),
                                "card_number" => $request->getCreditCard()->getCardNumber(),
                                "date_expiration" => $request->getCreditCard()->getDateExpiration(),
                                "flag" => $request->getCreditCard()->getFlag(),
                                "cvv" => $request->getCreditCard()->getCvv()
                            ]

                        ]
                ],
                [
                    'headers' => ['Content-Type' => 'application/json']
                ]
            );
            return new Payment(
                $response['message']['status']
            );
        } catch (\Exception $exception) {
            throw new PaymentGatewayException($exception);
        }
    }
}
