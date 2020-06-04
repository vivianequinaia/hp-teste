<?php

namespace HP\Modules\Purchases\Shopping\Responses;

interface ResponseInterface
{
    public function getStatus(): Status;
}
