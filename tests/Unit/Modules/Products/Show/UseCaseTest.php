<?php

namespace Tests\Unit\Modules\Products\Show;

use App\Adapters\LogMonologAdapter;
use HP\Modules\Products\Show\Entities\Product;
use HP\Modules\Products\Show\Entities\Purchase;
use HP\Modules\Products\Show\Exceptions\GetProductDataDatabaseException;
use HP\Modules\Products\Show\Exceptions\ProductNotFoundException;
use HP\Modules\Products\Show\Gateways\GetProductDataGateway;
use HP\Modules\Products\Show\Requests\Request;
use HP\Modules\Products\Show\Responses\Response;
use HP\Modules\Products\Show\Responses\Status;
use HP\Modules\Products\Show\Responses\Error\Response as ResponseError;
use HP\Modules\Products\Show\UseCase;
use Tests\TestCase;

class UseCaseTest extends TestCase
{
    public function testSuccessResponse()
    {
        $gatewayMock = $this->createMock(GetProductDataGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('getProduct')
            ->willReturn(ProductBoot::getProduct());

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getSuccessResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getProduct(), $response->getProduct());
    }

    public function testGenericError()
    {
        $gatewayMock = $this->createMock(GetProductDataGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('getProduct')->willThrowException(new \Exception());

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getGenericErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }

    public function testGetProductDataDatabaseError()
    {
        $gatewayMock = $this->createMock(GetProductDataGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('getProduct')->willThrowException(new GetProductDataDatabaseException(new \Exception()));

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getDatabaseErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }

    public function testProductNotFoundError()
    {
        $gatewayMock = $this->createMock(GetProductDataGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('getProduct')
            ->willThrowException((new ProductNotFoundException(new \Exception()))->setProductId(1));

        $useCase = new UseCase(
            $gatewayMock,
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
            ProductBoot::getProduct()
        );
    }

    public static function getGenericErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'A generic error occurred when trying get product.'
        );
    }

    public static function getDatabaseErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'Error while get product data.'
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

class ProductBoot
{
    public static function getProduct(): Product
    {
        return new Product(
            1,
            'name',
            15.75,
            4,
            PurchaseBoot::getPurchase()
        );
    }
}

class PurchaseBoot
{
    public static function getPurchase(): Purchase
    {
        return new Purchase(
            '2020-06-04',
            15.75
        );
    }
}

Class RequestBoot
{
    public static function getRequest()
    {
        return new Request(
            1
        );
    }
}
