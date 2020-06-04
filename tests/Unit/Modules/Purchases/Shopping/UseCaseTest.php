<?php

namespace Tests\Unit\Modules\Purchases\Shopping;

use App\Adapters\LogMonologAdapter;
use HP\Modules\Purchases\Shopping\Entities\CreditCard;
use HP\Modules\Purchases\Shopping\Entities\Payment;
use HP\Modules\Purchases\Shopping\Exceptions\GetProductAmountDatabaseException;
use HP\Modules\Purchases\Shopping\Exceptions\PaymentGatewayException;
use HP\Modules\Purchases\Shopping\Exceptions\ProductNotFoundException;
use HP\Modules\Purchases\Shopping\Exceptions\SavePurchaseDatabaseException;
use HP\Modules\Purchases\Shopping\Gateways\GetProductAmountGateway;
use HP\Modules\Purchases\Shopping\Gateways\SavePurchaseGateway;
use HP\Modules\Purchases\Shopping\Gateways\ShoppingPaymentGateway;
use HP\Modules\Purchases\Shopping\Requests\Request;
use HP\Modules\Purchases\Shopping\Responses\Response;
use HP\Modules\Purchases\Shopping\Responses\Status;
use HP\Modules\Purchases\Shopping\UseCase;
use HP\Modules\Purchases\Shopping\Responses\Error\Response as ResponseError;
use Tests\TestCase;

class UseCaseTest extends TestCase
{
    public function testSuccessResponse()
    {
        $getProductAmountGatewayMock = $this->createMock(GetProductAmountGateway::class);
        $shoppingPaymentGatewayMock = $this->createMock(ShoppingPaymentGateway::class);
        $savePurchaseGateway = $this->createMock(SavePurchaseGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $getProductAmountGatewayMock->expects($this->once())
            ->method('getAmountById')
            ->willReturn(10.45);
        $shoppingPaymentGatewayMock->expects($this->once())
            ->method('payment')
            ->willReturn(PaymentBoot::getPPayment());
        $savePurchaseGateway->expects($this->once())
            ->method('save');

        $useCase = new UseCase(
            $getProductAmountGatewayMock,
            $shoppingPaymentGatewayMock,
            $savePurchaseGateway,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getSuccessResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getPayment(), $response->getPayment());
    }

    public function testSavePurchaseDatabaseException()
    {
        $getProductAmountGatewayMock = $this->createMock(GetProductAmountGateway::class);
        $shoppingPaymentGatewayMock = $this->createMock(ShoppingPaymentGateway::class);
        $savePurchaseGateway = $this->createMock(SavePurchaseGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $getProductAmountGatewayMock->expects($this->once())
            ->method('getAmountById')
            ->willReturn(10.45);
        $shoppingPaymentGatewayMock->expects($this->once())
            ->method('payment')
            ->willReturn(PaymentBoot::getPPayment());
        $savePurchaseGateway->expects($this->once())
            ->method('save')
        ->willThrowException(new SavePurchaseDatabaseException(new \Exception()));

        $useCase = new UseCase(
            $getProductAmountGatewayMock,
            $shoppingPaymentGatewayMock,
            $savePurchaseGateway,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $useCase->getResponse();
    }

    public function testPaymentGatewayError()
    {
        $getProductAmountGatewayMock = $this->createMock(GetProductAmountGateway::class);
        $shoppingPaymentGatewayMock = $this->createMock(ShoppingPaymentGateway::class);
        $savePurchaseGateway = $this->createMock(SavePurchaseGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $getProductAmountGatewayMock->expects($this->once())
            ->method('getAmountById')
            ->willReturn(10.45);
        $shoppingPaymentGatewayMock->expects($this->once())
            ->method('payment')
            ->willThrowException(new PaymentGatewayException(new \Exception()));

        $useCase = new UseCase(
            $getProductAmountGatewayMock,
            $shoppingPaymentGatewayMock,
            $savePurchaseGateway,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getPaymentGatewayErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }

    public function testGenericError()
    {
        $getProductAmountGatewayMock = $this->createMock(GetProductAmountGateway::class);
        $shoppingPaymentGatewayMock = $this->createMock(ShoppingPaymentGateway::class);
        $savePurchaseGateway = $this->createMock(SavePurchaseGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $getProductAmountGatewayMock->expects($this->once())
            ->method('getAmountById')
            ->willThrowException(new \Exception());

        $useCase = new UseCase(
            $getProductAmountGatewayMock,
            $shoppingPaymentGatewayMock,
            $savePurchaseGateway,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getGenericErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }

    public function testGetProductAmountDatabaseError()
    {
        $getProductAmountGatewayMock = $this->createMock(GetProductAmountGateway::class);
        $shoppingPaymentGatewayMock = $this->createMock(ShoppingPaymentGateway::class);
        $savePurchaseGateway = $this->createMock(SavePurchaseGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $getProductAmountGatewayMock->expects($this->once())
            ->method('getAmountById')
            ->willThrowException(new GetProductAmountDatabaseException(new \Exception()));


        $useCase = new UseCase(
            $getProductAmountGatewayMock,
            $shoppingPaymentGatewayMock,
            $savePurchaseGateway,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getProductAmountDatabaseErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }

    public function testProductNotFoundError()
    {
        $getProductAmountGatewayMock = $this->createMock(GetProductAmountGateway::class);
        $shoppingPaymentGatewayMock = $this->createMock(ShoppingPaymentGateway::class);
        $savePurchaseGateway = $this->createMock(SavePurchaseGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $getProductAmountGatewayMock->expects($this->once())
            ->method('getAmountById')
            ->willThrowException((new ProductNotFoundException(new \Exception()))->setProductId(1));

        $useCase = new UseCase(
            $getProductAmountGatewayMock,
            $shoppingPaymentGatewayMock,
            $savePurchaseGateway,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getProductNotFoundErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }
}

class ResponseBoot
{
    public static function getSuccessResponse(): Response
    {
        return new Response(
            new Status(200, 'Ok'),
            PaymentBoot::getPPayment()
        );
    }

    public static function getPaymentGatewayErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'Error when trying to connect to the payment gateway'
        );
    }

    public static function getGenericErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'A generic error occurred while trying to pay.'
        );
    }

    public static function getProductAmountDatabaseErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'Error while find product amount.'
        );
    }

    public static function getProductNotFoundErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(404, 'Not Found'),
            'This product does not exist.'
        );
    }
}

class PaymentBoot
{
    public static function getPPayment(): Payment
    {
        return new Payment(
            'Aprovado'
        );
    }
}

class CreditCardBoot
{
    public static function getCreditCard(): CreditCard
    {
        return new CreditCard(
            "John Doe",
            "4111111111111111",
            "12/2018",
            "VISA",
            "123"
        );
    }
}

class RequestBoot
{
    public static function getRequest()
    {
        return new Request(
            1,
            4,
            CreditCardBoot::getCreditCard()
        );
    }
}
