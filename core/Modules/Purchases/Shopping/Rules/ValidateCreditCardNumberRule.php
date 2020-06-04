<?php

namespace HP\Modules\Purchases\Shopping\Rules;

use HP\Modules\Purchases\Shopping\Requests\Request;
use HP\Modules\Purchases\Shopping\Validators\CreditCardNumberValidator;

class ValidateCreditCardNumberRule
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply()
    {
        (new CreditCardNumberValidator())->validate($this->request->getCreditCard()->getCardNumber());
    }
}
