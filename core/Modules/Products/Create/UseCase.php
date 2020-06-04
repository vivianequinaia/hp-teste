<?php

namespace HP\Modules\Products\Create;

use HP\Dependencies\LogInterface;
use HP\Modules\Products\Create\Builders\Builder;
use HP\Modules\Products\Create\Exceptions\CreateProductDatabaseException;
use HP\Modules\Products\Create\Gateways\CreateProductGateway;
use HP\Modules\Products\Create\Requests\Request;
use HP\Modules\Products\Create\Responses\Error\Response;
use HP\Modules\Products\Create\Responses\ResponseInterface;
use HP\Modules\Products\Create\Responses\Status;
use HP\Modules\Products\Create\Rules\CreateProductRule;

final class UseCase
{
    private $createProductGateway;
    private $logger;
    private $response;

    public function __construct(CreateProductGateway $createProductGateway, LogInterface $logger)
    {
        $this->createProductGateway = $createProductGateway;
        $this->logger = $logger;
    }

    public function execute(Request $request)
    {
        try {
            $this->logger->info('[Products::Create] Init Use Case.');

            $this->response = (new Builder())
                ->withCreateProductRule(
                    new CreateProductRule(
                        $this->createProductGateway,
                        $request
                    )
                )->build();
            $this->logger->info('[Products::Create] Finish Use Case.');
        } catch (CreateProductDatabaseException $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Products::Create] An error occurred while insert product on database.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "previous" => [
                        "exception" => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                        "message" => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                    ]
                ]
            );
        } catch (\Exception $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Products::Create] A generic error occurred when trying to create a new product.',
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
