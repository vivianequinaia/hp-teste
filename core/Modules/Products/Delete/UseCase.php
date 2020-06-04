<?php

namespace HP\Modules\Products\Delete;

use HP\Dependencies\LogInterface;
use HP\Modules\Products\Delete\Builders\Builder;
use HP\Modules\Products\Delete\Exceptions\DeleteProductDatabaseException;
use HP\Modules\Products\Delete\Gateways\DeleteProductGateway;
use HP\Modules\Products\Delete\Requests\Request;
use HP\Modules\Products\Delete\Responses\Error\Response;
use HP\Modules\Products\Delete\Responses\ResponseInterface;
use HP\Modules\Products\Delete\Responses\Status;
use HP\Modules\Products\Delete\Rules\DeleteProductRule;

final class UseCase
{
    private $deleteProductGateway;
    private $logger;
    private $response;

    public function __construct(DeleteProductGateway $deleteProductGateway, LogInterface $logger)
    {
        $this->deleteProductGateway = $deleteProductGateway;
        $this->logger = $logger;
    }

    public function execute(Request $request)
    {
        try {
            $this->logger->info('[Products::Delete] Init Use Case.');

            $this->response = (new Builder())
                ->withDeleteProductRule(
                    new DeleteProductRule(
                        $this->deleteProductGateway,
                        $request
                    )
                )->build();
            $this->logger->info('[Products::Delete] Finish Use Case.');
        } catch (DeleteProductDatabaseException $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Products::Delete] An error occurred while delete product from database.',
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
                'A generic error occurred when trying to delete product.'
            );
            $this->logger->error(
                '[Products::Delete] A generic error occurred when trying to delete product.',
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
