<?php

namespace HP\Modules\Products\Delete\Gateways;

use HP\Modules\Products\Delete\Requests\Request;

interface DeleteProductGateway
{
    public function delete(Request $request): void;
}
