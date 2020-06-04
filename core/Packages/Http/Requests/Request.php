<?php

namespace HP\Packages\Http\Requests;

use HP\Packages\Http\Adapters\GuzzleAdapter;
use HP\Packages\Http\Gateways\RequestGateway;
use HP\Packages\Http\Validations;

class Request extends GuzzleAdapter implements RequestGateway
{
    use Validations\RequestTrait;

    private $data;
    private $uri;
    private $method;
    private $headers = null;
    private $response;

    public function setMethod(string $method): RequestGateway
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setUri(string $uri): RequestGateway
    {
        $this->uri = $uri;
        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setData(array $data): RequestGateway
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setHeaders(array $headers): RequestGateway
    {
        $this->headers = $headers;
        return $this;
    }

    public function getHeaders(): string
    {
        return $this->headers;
    }

    public function request()
    {
        $this->validateNonOptionalAttributes();
        $this->response = parent::{$this->method}($this->uri, $this->data, $this->headers);
        return $this->response;
    }

    public function getResponse(): string
    {
        return $this->response
            ->getBody()
            ->getContents();
    }
}
