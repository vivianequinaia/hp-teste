<?php

namespace HP\Packages\Http\Gateways;

interface RequestGateway
{
    public function setUri(string $uri): RequestGateway;

    public function setData(array $data): RequestGateway;

    public function setHeaders(array $headers): RequestGateway;

    public function request();
}
