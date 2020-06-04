<?php

namespace HP\Dependencies;

interface HttpInterface
{
    public function post(string $uri, array $data, array $headers = []): ?array;
}
