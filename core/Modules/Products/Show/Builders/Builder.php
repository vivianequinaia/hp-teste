<?php

namespace HP\Modules\Products\Show\Builders;

use HP\Modules\Products\Show\Responses\Response;
use HP\Modules\Products\Show\Responses\Status;
use HP\Modules\Products\Show\Rules\GetProductDataRule;

class Builder
{
    private $getProductDataRule;

    public function withGetProductDataRule(GetProductDataRule $getProductDataRule): Builder
    {
        $this->getProductDataRule = $getProductDataRule;
        return $this;
    }

    public function build(): Response
    {
        return new Response(
            new Status(200, 'Ok'),
            $this->getProductDataRule->apply()
        );
    }
}
