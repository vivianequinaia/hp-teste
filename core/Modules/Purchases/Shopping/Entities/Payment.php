<?php

namespace HP\Modules\Purchases\Shopping\Entities;

class Payment
{
    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
