<?php

namespace HP\Dependencies;

interface ConfigInterface
{
    public function getPaymentGatewayUrl(): string;
}
