<?php

namespace HP\Modules\Products\Show;

use HP\Dependencies\LogInterface;
use HP\Modules\Products\Show\Builders\Builder;
use HP\Modules\Products\Show\Exceptions\GetProductDataDatabaseException;
use HP\Modules\Products\Show\Exceptions\ProductNotFoundException;
use HP\Modules\Products\Show\Gateways\GetProductDataGateway;
use HP\Modules\Products\Show\Requests\Request;
use HP\Modules\Products\Show\Responses\Error\Response;
use HP\Modules\Products\Show\Responses\ResponseInterface;
use HP\Modules\Products\Show\Responses\Status;
use HP\Modules\Products\Show\Rules\GetProductDataRule;

final class UseCase
{
    private $getProductDataGateway;
    private $logger;
    private $response;

    public function __construct(GetProductDataGateway $getProductDataGateway, LogInterface $logger)
    {
        $this->getProductDataGateway = $getProductDataGateway;
        $this->logger = $logger;
    }

    public function execute(Request $request)
    {
        try {
            $this->logger->info('[Products::Show] Init Use Case.');

            $this->response = (new Builder())
                ->withGetProductDataRule(
                    new GetProductDataRule(
                        $this->getProductDataGateway,
                        $request
                    )
                )->build();
            $this->logger->info('[Products::Show] Finish Use Case.');
        } catch (GetProductDataDatabaseException $exception) {
            $this->response = new Response(
                new Status(500, 'Internal Server Error'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Products::Show] An error occurred while get product from database.',
                [
                    "exception" => get_class($exception),
                    "message" => $exception->getMessage(),
                    "previous" => [
                        "exception" => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                        "message" => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                    ]
                ]
            );
        } catch (ProductNotFoundException $exception) {
            $this->response = new Response(
                new Status(404, 'Not Found'),
                $exception->getMessage()
            );
            $this->logger->error(
                '[Products::Show] This product does not exist.',
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
                'A generic error occurred when trying get product.'
            );
            $this->logger->error(
                '[Products::Show] A generic error occurred when trying get product.',
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
