<?php

namespace HP\Modules\Purchases\Shopping;

use HP\Dependencies\LogInterface;
use HP\Modules\Purchases\Shopping\Builders\Builder;
use HP\Modules\Purchases\Shopping\Exceptions\GetProductAmountDatabaseException;
use HP\Modules\Purchases\Shopping\Exceptions\PaymentGatewayException;
use HP\Modules\Purchases\Shopping\Exceptions\ProductNotFoundException;
use HP\Modules\Purchases\Shopping\Exceptions\SavePurchaseDatabaseException;
use HP\Modules\Purchases\Shopping\Gateways\GetProductAmountGateway;
use HP\Modules\Purchases\Shopping\Gateways\SavePurchaseGateway;
use HP\Modules\Purchases\Shopping\Gateways\ShoppingPaymentGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;
use HP\Modules\Purchases\Shopping\Responses\Error\Response;
use HP\Modules\Purchases\Shopping\Responses\ResponseInterface;
use HP\Modules\Purchases\Shopping\Responses\Status;
use HP\Modules\Purchases\Shopping\Rules\GetProductAmountRule;
use HP\Modules\Purchases\Shopping\Rules\SavePurchaseRule;
use HP\Modules\Purchases\Shopping\Rules\ShoppingPaymentRule;
use HP\Modules\Purchases\Shopping\Rules\ValidateCreditCardNumberRule;

final class UseCase
{
    private $getProductAmountGateway;
    private $shoppingPaymentGateway;
    private $savePurchaseGateway;
    private $logger;
    private $response;

    public function __construct(
        GetProductAmountGateway $getProductAmountGateway,
        ShoppingPaymentGateway $shoppingPaymentGateway,
        SavePurchaseGateway $savePurchaseGateway,
        LogInterface $logger
    )
    {
        $this->getProductAmountGateway = $getProductAmountGateway;
        $this->shoppingPaymentGateway = $shoppingPaymentGateway;
        $this->savePurchaseGateway = $savePurchaseGateway;
        $this->logger = $logger;
    }

    public function execute(Request $request)
    {
        try {
            $this->logger->info('[Purchases::Shopping] Init Use Case.');

            $this->response = (new Builder())
                ->withValidateCreditCardNumberRule(
                    new ValidateCreditCardNumberRule(
                        $request
                    )
                )->withgetProductAmountRule(
                    new GetProductAmountRule(
                        $this->getProductAmountGateway,
                        $request
                    )
                )->withShoppingPaymentRule(
                    new ShoppingPaymentRule(
                        $this->shoppingPaymentGateway,
                        $request
                    )
                )->withSavePurchaseRule(
                    new SavePurchaseRule(
                        $this->savePurchaseGateway,
                        $request
                    )
                )
                ->build();
            $this->logger->info('[Purchases::Shopping] Finish Use Case.');
        } catch (GetProductAmountDatabaseException $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Purchases::Shopping] An error occurred while get product amount from database.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "data" => [
                        "product_id" => $exception->getProductId()
                    ]
                ]
            );
        } catch (PaymentGatewayException $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Purchases::Shopping] Error when trying to connect to the payment gateway.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "data" => [
                        "product_id" => $exception->getProductId()
                    ]
                ]
            );
        } catch (ProductNotFoundException $exception) {
            $this->response = new Response(
                new Status(404, 'Not Found'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Purchases::Shopping] This product does not exist.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "data" => [
                        "product_id" => $exception->getProductId()
                    ]
                ]
            );
        } catch (SavePurchaseDatabaseException $exception) {
            $this->logger->error(
                '[Purchases::Shopping] An error occurred while save purchase on database.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "data" => [
                        "product_id" => $exception->getProductId()
                    ]
                ]
            );
        } catch (\Exception $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Purchases::Shopping] A generic error occurred while trying to pay.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                ]
            );
        }
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
