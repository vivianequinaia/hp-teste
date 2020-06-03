<?php

namespace HP\Modules\Products\Create\Builders;

use HP\Modules\Products\Create\Responses\Response;
use HP\Modules\Products\Create\Responses\Status;
use HP\Modules\Products\Create\Rules\CreateProductRule;

class Builder
{
    private $createProductRule;

    public function withCreateProductRule(CreateProductRule $createProductRule): Builder
    {
        $this->createProductRule = $createProductRule;
        return $this;
    }

    public function build(): Response
    {
        $this->createProductRule->apply();

        return new Response(
            new Status(201, 'Created')
        );
    }
}
