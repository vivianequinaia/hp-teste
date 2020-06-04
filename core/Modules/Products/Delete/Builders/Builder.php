<?php

namespace HP\Modules\Products\Delete\Builders;

use HP\Modules\Products\Delete\Responses\Response;
use HP\Modules\Products\Delete\Responses\Status;
use HP\Modules\Products\Delete\Rules\DeleteProductRule;

class Builder
{
    private $deleteProductRule;

    public function withDeleteProductRule(DeleteProductRule $deleteProductRule): Builder
    {
        $this->deleteProductRule = $deleteProductRule;
        return $this;
    }

    public function build(): Response
    {
        $this->deleteProductRule->apply();

        return new Response(
            new Status(200, 'Ok'),
            'Product deleted with success'
        );
    }
}
