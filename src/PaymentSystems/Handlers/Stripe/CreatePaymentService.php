<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use YevgeniiV\PsPackage\Enums\Currency;
use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;

class CreatePaymentService
{
    /**
     * @throws ApiErrorException
     */
    public function handle(StripeClient $stripeClient, MakePaymentDTO $makePaymentDTO): string
    {
        $result = $stripeClient->paymentIntents->create([
            'amount' => $makePaymentDTO->getAmount() * 100,
            'currency' => $this->getCurrency($makePaymentDTO->getCurrency()),
        ]);

        return $result->client_secret;
    }

    private function getCurrency(Currency $currency): string
    {
        return match ($currency) {
            Currency::USD => 'usd',
            Currency::EUR => 'eur',
        };
    }
}
