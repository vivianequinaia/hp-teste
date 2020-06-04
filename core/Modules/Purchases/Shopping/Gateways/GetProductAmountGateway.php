<?php

namespace HP\Modules\Purchases\Shopping\Gateways;

interface GetProductAmountGateway
{
    public function getAmountById(int $id): float;
}
