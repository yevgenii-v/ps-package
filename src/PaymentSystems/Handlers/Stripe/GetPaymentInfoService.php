<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use YevgeniiV\PsPackage\Enums\Currency;
use YevgeniiV\PsPackage\Enums\PaymentSystem;
use YevgeniiV\PsPackage\Enums\Status;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PayerDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PaymentInfoDTO;

class GetPaymentInfoService
{
    /**
     * @throws ApiErrorException
     */
    public function handle(StripeClient $stripeClient, string $paymentId): PaymentInfoDTO
    {
        $response = $stripeClient->paymentIntents->retrieve($paymentId);
        $resultArray = ($response->toArray());

        return new PaymentInfoDTO(
            $this->getStatus($resultArray['status']),
            PaymentSystem::STRIPE,
            $resultArray['client_secret'],
            $resultArray['id'],
            $resultArray['amount_received'] / 100,
            $this->getCurrency($resultArray['currency']),
            $resultArray['created'],
            new PayerDTO(
                '',
                null,
                null,
                null,
            ),
        );
    }

    private function getStatus(string $status): Status
    {
        return match ($status) {
            'succeeded' => Status::SUCCESS,
            default => Status::FAILED,
        };
    }

    private function getCurrency(string $status): Currency
    {
        return match ($status) {
            'usd' => Currency::USD,
            default => Currency::EUR,
        };
    }
}
