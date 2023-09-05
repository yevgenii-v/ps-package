<?php

namespace YevgeniiV\PsPackage\PaymentSystems\DTO;

use YevgeniiV\PsPackage\Enums\Currency;

class MakePaymentDTO
{
    /**
     * @param float $amount
     * @param Currency $currency
     * @param string $description
     */
    public function __construct(
        protected float $amount,
        protected Currency $currency,
        protected string $description = '',
    ) {
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
