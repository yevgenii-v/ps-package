<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Paypal;

use Srmklive\PayPal\Services\PayPal;
use Throwable;
use YevgeniiV\PsPackage\Enums\Currency;
use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;

class CreatePaymentService
{
    /**
     * @throws Throwable
     */
    public function handle(PayPal $payPal, MakePaymentDTO $makePaymentDTO): string
    {
        $response = $payPal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $this->getCurrency($makePaymentDTO->getCurrency()),
                        "value" => number_format($makePaymentDTO->getAmount(), 2, '.')
                    ]
                ]
            ]
        ]);

        $result = ['id' => ''];

        if (isset($response['id']) && $response['id'] != null) {
            $result = ['id' => $response['id']];
        }

        return json_encode($result, true);
    }

    private function getCurrency(Currency $currency): string
    {
        return match ($currency) {
            Currency::USD => 'USD',
            Currency::EUR => 'EUR',
        };
    }
}
