<?php

namespace HP\Packages\Http;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HP\Dependencies\LogInterface;

class GuzzleRetry
{
    private $logger;
    private $retry;

    public function __construct(LogInterface $logger, int $retry = 3)
    {
        $this->retry = $retry;
        $this->logger = $logger;
    }

    public function retryDecider(): callable
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            if ($retries >= $this->retry) {
                return false;
            }
            $shouldRetry = false;

            if ($exception instanceof ConnectException) {
                $shouldRetry = true;
            }

            if ($response && $response->getStatusCode() >= 500) {
                $shouldRetry = true;
            }

            if ($shouldRetry) {
                $this->logger->warning(
                    sprintf(
                        'Retrying %s %s %s/%s, %s',
                        $request->getMethod(),
                        $request->getUri(),
                        $retries + 1,
                        $this->retry,
                        $response ? 'status code: ' . $response->getStatusCode() :
                            $exception->getMessage()
                    )
                );
            }
            return $shouldRetry;
        };
    }

    public function retryDelay(): callable
    {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }
}
