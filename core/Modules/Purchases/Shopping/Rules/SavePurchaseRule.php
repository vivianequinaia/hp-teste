<?php

namespace HP\Modules\Purchases\Shopping\Rules;

use HP\Dependencies\LogInterface;
use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Exceptions\SavePurchaseDatabaseException;
use HP\Modules\Purchases\Shopping\Gateways\SavePurchaseGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;
use HP\Modules\Purchases\Shopping\Resolvers\PaymentTotalResolver;

class SavePurchaseRule
{
    private $savePurchaseGateway;
    private $request;
    private $amount;
    private $payment;
    private $logger;

    public function __construct(SavePurchaseGateway $savePurchaseGateway, Request $request, LogInterface $logger)
    {
        $this->savePurchaseGateway = $savePurchaseGateway;
        $this->request = $request;
        $this->logger = $logger;
    }

    public function __invoke(float $amount, Payment $payment)
    {
        $this->amount = $amount;
        $this->payment = $payment;
        return $this;
    }

    public function apply(): void
    {
        if ($this->payment->getStatus() == 'Aprovado') {
            $total = (new PaymentTotalResolver($this->request->getQuantityPurchased(), $this->amount))->resolve();
            try {
                $this->savePurchaseGateway->save($this->request, $total);
            } catch (SavePurchaseDatabaseException $exception) {
                $this->logger->warning(
                    '[Purchases/Shopping::SavePurchaseRule] An error occurred while save purchase on database.',
                    [
                        "exception" => get_class($exception),
                        "message" => $exception->getMessage(),
                        "previous" => [
                            "exception" => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                            "message" => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                        ],
                        "data" => [
                            'product_id' => $this->request->getProductId(),
                            'quantity' => $this->request->getQuantityPurchased()
                        ]
                    ]
                );
            }
        }
    }
}
