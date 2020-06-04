<?php

namespace HP\Packages\Http;

use HP\Packages\Http\Gateways\RequestGateway;

class Client
{
    private $gateway;

    public function __construct(RequestGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function configure(): RequestGateway
    {
        return $this->gateway;
    }

    public function request()
    {
        return $this->gateway->request();
    }
}
