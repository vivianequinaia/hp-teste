<?php

namespace HP\Packages\Http\Adapters;

use App\Adapters\LogMonologAdapter;
use HP\Packages\Http\GuzzleRetry;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class GuzzleAdapter
{
    protected $client;

    public function __construct(array $options)
    {
        $retry = new GuzzleRetry(new LogMonologAdapter());
        $handlerStack = HandlerStack::create(new CurlHandler());
        $handlerStack->push(Middleware::retry($retry->retryDecider(), $retry->retryDelay()));
        $options['handler'] = $handlerStack;
        $options['verify'] = false;
        $this->client = new \GuzzleHttp\Client($options);
    }

    public function post(string $endpoint, array $data, ?array $headers)
    {
        if (empty($headers) || is_null($headers)) {
            $headers = array();
        }
        $data = array_merge($data, [
            'headers' => $headers
        ]);
        return $this->client->post($endpoint, $data);
    }
}
