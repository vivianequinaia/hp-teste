<?php

namespace HP\Modules\Purchases\Shopping\Validators;

use HP\Modules\Purchases\Shopping\Exceptions\InvalidCreditCardException;

class CreditCardNumberValidator
{
    public function validate(string $number): bool
    {
        $str = '';
        foreach (array_reverse(str_split($number)) as $i => $c) $str .= ($i % 2 ? $c * 2 : $c);
        if(array_sum(str_split($str)) % 10 == 0) {
            return true;
        }
        throw new InvalidCreditCardException();
    }
}
