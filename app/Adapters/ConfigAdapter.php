<?php

namespace App\Adapters;

use App\Adapters\Exceptions\NullConfigException;

class ConfigAdapter
{
    public function getPaymentGatewayUrl(): string
    {
        return $this->getConfig('PAYMENT_GATEWAY_URL');
    }

    public function getConfig(string $name): string
    {
        $config = env($name);

        if (is_null($config)) {
            throw new NullConfigException(sprintf("The informed config %s not exists in .env file", $name));
        }

        return $config;
    }
}
