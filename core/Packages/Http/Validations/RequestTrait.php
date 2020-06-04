<?php

namespace HP\Packages\Http\Validations;

use HP\Packages\Http\Exceptions\RequestAttributeNotSetException;

trait RequestTrait
{
    protected function validateNonOptionalAttributes()
    {
        if (is_null($this->method) || empty($this->method)) {
            throw new RequestAttributeNotSetException('The attribute method is not set.');
        }
        if (is_null($this->uri) || empty($this->uri)) {
            throw new RequestAttributeNotSetException('The attribute uri is not set.');
        }
        if (is_null($this->data) || empty($this->data)) {
            throw new RequestAttributeNotSetException('The attribute data is not set.');
        }
    }
}
