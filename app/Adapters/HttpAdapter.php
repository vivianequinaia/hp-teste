<?php

namespace App\Adapters;

use HP\Dependencies\HttpInterface;
use HP\Packages\Http\Client;
use HP\Packages\Http\Requests\Request;

class HttpAdapter implements HttpInterface
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(new Request([]));
    }

    public function post(string $uri, array $data, array $headers = []): ?array
    {
        $httpConnector = $this->client
            ->configure()
            ->setMethod('POST')
            ->setUri($uri)
            ->setHeaders($headers)
            ->setData($data);

        $httpConnector->request();

        return json_decode($httpConnector->getResponse(), true);
    }
}
