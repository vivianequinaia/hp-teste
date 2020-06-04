<?php

namespace HP\Modules\Purchases\Shopping\Builders;

use HP\Modules\Purchases\Shopping\Responses\Response;
use HP\Modules\Purchases\Shopping\Responses\Status;
use HP\Modules\Purchases\Shopping\Rules\GetProductAmountRule;
use HP\Modules\Purchases\Shopping\Rules\SavePurchaseRule;
use HP\Modules\Purchases\Shopping\Rules\ShoppingPaymentRule;
use HP\Modules\Purchases\Shopping\Rules\ValidateCreditCardNumberRule;

class Builder
{
    private $validateCreditCardNumberRule;
    private $getProductAmountRule;
    private $shoppingPaymentRule;
    private $savePurchaseRule;

    public function withValidateCreditCardNumberRule(
        ValidateCreditCardNumberRule $validateCreditCardNumberRule
    ): Builder {
        $this->validateCreditCardNumberRule = $validateCreditCardNumberRule;
        return $this;
    }

    public function withgetProductAmountRule(GetProductAmountRule $getProductAmountRule): Builder
    {
        $this->getProductAmountRule = $getProductAmountRule;
        return $this;
    }

    public function withShoppingPaymentRule(ShoppingPaymentRule $shoppingPaymentRule): Builder
    {
        $this->shoppingPaymentRule = $shoppingPaymentRule;
        return $this;
    }

    public function withSavePurchaseRule(SavePurchaseRule $savePurchaseRule): Builder
    {
        $this->savePurchaseRule = $savePurchaseRule;
        return $this;
    }

    public function build(): Response
    {
        $this->validateCreditCardNumberRule->apply();
        $amount = $this->getProductAmountRule->apply();
        $payment = ($this->shoppingPaymentRule)($amount)->apply();
        ($this->savePurchaseRule)($amount, $payment)->apply();

        return new Response(
            new Status(200, 'Ok'),
            $payment
        );
    }
}
