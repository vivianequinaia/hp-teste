<?php

namespace Tests\Unit\Modules\Products\Listing;

use App\Adapters\LogMonologAdapter;
use HP\Modules\Products\Listing\Collections\ProductCollection;
use HP\Modules\Products\Listing\Entities\Product;
use HP\Modules\Products\Listing\Exceptions\FindProductDatabaseException;
use HP\Modules\Products\Listing\Gateways\FindProductGateway;
use HP\Modules\Products\Listing\Responses\Response;
use HP\Modules\Products\Listing\Responses\Status;
use HP\Modules\Products\Listing\UseCase;
use Tests\TestCase;
use HP\Modules\Products\Listing\Responses\Error\Response as ResponseError;

class UseCaseTest extends TestCase
{
    public function testSuccessResponse()
    {
        $gatewayMock = $this->createMock(FindProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('findProducts')
            ->willReturn(ProductCollectionBoot::getProductCollection());

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute();

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getSuccessResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getProductCollection(), $response->getProductCollection());
    }

    public function testGenericError()
    {
        $gatewayMock = $this->createMock(FindProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('findProducts')->willThrowException(new \Exception());

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute();

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getGenericErrorResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getError(), $response->getError());
    }

    public function testFindProductDatabaseError()
    {
        $gatewayMock = $this->createMock(FindProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('findProducts')->willThrowException(new FindProductDatabaseException(new \Exception()));

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute();

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getDatabaseErrorResponse();

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
            ProductCollectionBoot::getProductCollection()
        );
    }

    public static function getGenericErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'A generic error occurred when trying listing products.'
        );
    }

    public static function getDatabaseErrorResponse(): ResponseError
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'Error while find products.'
        );
    }
}

class ProductBoot
{
    public static function getProduct(): Product
    {
        return new Product(
            'name',
            15.75,
            4
        );
    }
}

class ProductCollectionBoot
{
    public static function getProductCollection(): ProductCollection
    {
        $productColletion = new ProductCollection();
        $productColletion->add(ProductBoot::getProduct());
        return $productColletion;
    }
}
