<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Paypal;

use Srmklive\PayPal\Services\PayPal;
use Throwable;
use YevgeniiV\PsPackage\Enums\Currency;
use YevgeniiV\PsPackage\Enums\PaymentSystem;
use YevgeniiV\PsPackage\Enums\Status;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PayerDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PaymentInfoDTO;

class GetPaymentInfoService
{
    /**
     * @throws Throwable
     */
    public function handle(PayPal $payPal, string $paymentId): PaymentInfoDTO
    {
        $response = $payPal->capturePaymentOrder($paymentId);
        return new PaymentInfoDTO(
            $this->getStatus($response['status']),
            PaymentSystem::PAYPAL,
            $response['purchase_units']['0']['reference_id'],
            $response['purchase_units']['0']['payments']['captures']['0']['id'] ?? '',
            $response['purchase_units']['0']['payments']['captures']['0']['amount']['value'] ?? '',
            $this->getCurrency(
                $response['purchase_units']['0']['payments']['captures']['0']['amount']['currency_code'] ?? ''
            ),
            strtotime($response['purchase_units']['0']['payments']['captures']['0']['create_time'] ?? time()),
            new PayerDTO(
                ($response['name']['given_name'] ?? '') . ' ' . ($response['name']['surname'] ?? ''),
                $response['email_address'] ?? null,
                null,
                null
            ),
        );
    }

    private function getStatus(string $status): Status
    {
        return match ($status) {
            'COMPLETED' => Status::SUCCESS,
            default => Status::FAILED,
        };
    }

    private function getCurrency(string $status): Currency
    {
        return match ($status) {
            'USD' => Currency::USD,
            default => Currency::EUR,
        };
    }
}
