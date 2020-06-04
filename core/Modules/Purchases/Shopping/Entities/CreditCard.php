<?php

namespace HP\Modules\Purchases\Shopping\Entities;

class CreditCard
{
    private $owner;
    private $cardNumber;
    private $dateExpiration;
    private $flag;
    private $cvv;

    public function __construct(string $owner, string $cardNumber, string $dateExpiration, string $flag, string $cvv)
    {
        $this->owner = $owner;
        $this->cardNumber = $cardNumber;
        $this->dateExpiration = $dateExpiration;
        $this->flag = $flag;
        $this->cvv = $cvv;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getDateExpiration(): string
    {
        return $this->dateExpiration;
    }

    public function getFlag(): string
    {
        return $this->flag;
    }

    public function getCvv(): string
    {
        return $this->cvv;
    }
}
