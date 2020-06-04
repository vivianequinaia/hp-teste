<?php

namespace Tests\Unit\Modules\Products\Create;

use App\Adapters\LogMonologAdapter;
use HP\Modules\Products\Create\Exceptions\CreateProductDatabaseException;
use HP\Modules\Products\Create\Gateways\CreateProductGateway;
use HP\Modules\Products\Create\Requests\Request;
use HP\Modules\Products\Create\Responses\Response;
use HP\Modules\Products\Create\Responses\Status;
use HP\Modules\Products\Create\UseCase;
use Tests\TestCase;
use HP\Modules\Products\Create\Responses\Error\Response as ResponseError;

class UseCaseTest extends TestCase
{
    public function testSuccessResponse()
    {
        $gatewayMock = $this->createMock(CreateProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('create');

        $useCase = new UseCase(
            $gatewayMock,
            $loggerMock
        );

        $useCase->execute(RequestBoot::getRequest());

        $response = $useCase->getResponse();
        $expectedResponse = ResponseBoot::getSuccessResponse();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedResponse->getStatus(), $response->getStatus());
    }

    public function testGenericError()
    {
        $gatewayMock = $this->createMock(CreateProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('create')->willThrowException(new \Exception());

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

    public function testCreateProductDatabaseError()
    {
        $gatewayMock = $this->createMock(CreateProductGateway::class);
        $loggerMock = $this->createMock(LogMonologAdapter::class);

        $gatewayMock->expects($this->once())
            ->method('create')->willThrowException(new CreateProductDatabaseException(new \Exception()));

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
            'name',
            15.36,
            2
        );
    }
}

Class ResponseBoot
{
    public static function getSuccessResponse()
    {
        return new Response(
            new Status(201, 'Created')
        );
    }

    public static function getGenericErrorResponse()
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'A generic error occurred when trying to create a new product.'
        );
    }

    public static function getDatabaseErrorResponse()
    {
        return new ResponseError(
            new Status(500, 'Internal Server Error'),
            'Error while creating product.'
        );
    }
}
