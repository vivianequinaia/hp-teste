<?php

namespace Tests\Unit\Modules\Products\Delete;

use App\Adapters\LogMonologAdapter;
use HP\Modules\Products\Delete\Exceptions\DeleteProductDatabaseException;
use HP\Modules\Products\Delete\Gateways\DeleteProductGateway;
use HP\Modules\Products\Delete\Requests\Request;
use HP\Modules\Products\Delete\Responses\Response;
use HP\Modules\Products\Delete\Responses\Error\Response as ResponseError;
use HP\Modules\Products\Delete\Responses\Status;
use HP\Modules\Products\Delete\UseCase;
use Tests\TestCase;

class UseCaseTest extends TestCase
{
    public function testSuccessResponse()
    {
        $gatewayMock = $this->createMock(DeleteProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('delete');

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getSuccessResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
        $this->assertEquals($expectedResponse->getMessage(), $response->getMessage());
    }

    public function testGenericError()
    {
        $gatewayMock = $this->createMock(DeleteProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('delete')->willThrowException(new \Exception());

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

    public function testDeleteProductDatabaseError()
    {
        $gatewayMock = $this->createMock(DeleteProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('delete')->willThrowException(new DeleteProductDatabaseException(new \Exception()));

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

Class ResponseBoot
{
    public static function getSuccessResponse()
    {
        return new Response(
            new Status(200, 'Ok'),
            'Product deleted with success'
        );
    }

    public static function getGenericErrorResponse()
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'A generic error occurred when trying to delete product.'
        );
    }

    public static function getDatabaseErrorResponse()
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'Error while deleting product.'
        );
    }
}
