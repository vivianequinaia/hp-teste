<?php

namespace HP\Packages\Http\Factories;

use HP\Packages\Http\Client;
use HP\Packages\Http\Requests;

class RequestGuzzleFactory
{
    public function create(string $endpoint, array $data, ?array $headers)
    {
        $client = new Client(new Requests\Request([]));
        $client->configure()
            ->setEndpoint($endpoint)
            ->setData($data)
            ->setHeaders($headers);
        return $client;
    }
}
