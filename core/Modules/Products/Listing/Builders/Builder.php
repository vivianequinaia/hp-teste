<?php

namespace HP\Modules\Products\Listing\Builders;

use HP\Modules\Products\Listing\Responses\Response;
use HP\Modules\Products\Listing\Responses\Status;
use HP\Modules\Products\Listing\Rules\FindProductRule;

class Builder
{
    private $findProductRule;

    public function withFindProductRule(FindProductRule $findProductRule): Builder
    {
        $this->findProductRule = $findProductRule;
        return $this;
    }

    public function build(): Response
    {
        return new Response(
            new Status(200, 'Ok'),
            $this->findProductRule->apply()
        );
    }
}
