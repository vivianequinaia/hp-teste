<?php

namespace HP\Modules\Products\Listing;

use HP\Dependencies\LogInterface;
use HP\Modules\Products\Listing\Builders\Builder;
use HP\Modules\Products\Listing\Exceptions\FindProductDatabaseException;
use HP\Modules\Products\Listing\Gateways\FindProductGateway;
use HP\Modules\Products\Listing\Responses\Error\Response;
use HP\Modules\Products\Listing\Responses\ResponseInterface;
use HP\Modules\Products\Listing\Responses\Status;
use HP\Modules\Products\Listing\Rules\FindProductRule;

final class UseCase
{
    private $findProductGateway;
    private $logger;
    private $response;

    public function __construct(FindProductGateway $findProductGateway, LogInterface $logger)
    {
        $this->findProductGateway = $findProductGateway;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $this->logger->info('[Products::Listing] Init Use Case.');

            $this->response = (new Builder())
                ->withFindProductRule(
                    new FindProductRule(
                        $this->findProductGateway,
                    )
                )->build();
            $this->logger->info('[Products::Listing] Finish Use Case.');
        } catch (FindProductDatabaseException $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Products::Listing] An error occurred while find for products on database.',
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
                '[Products::Listing] A generic error occurred when trying listing products.',
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
