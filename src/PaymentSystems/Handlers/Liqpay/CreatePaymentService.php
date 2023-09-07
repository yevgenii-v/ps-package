<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Liqpay;

use YevgeniiV\PsPackage\Enums\Currency;
use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;

class CreatePaymentService
{
    public function handle(Liqpay $liqpay, MakePaymentDTO $makePaymentDTO): string
    {
        $data = $liqpay->cnb_form_raw([
                    'version' => 3,
                    'amount' => $makePaymentDTO->getAmount(),
                    'currency' => $this->getCurrency($makePaymentDTO->getCurrency()),
                    'description' => $makePaymentDTO->getDescription(),
                    'order_id' => $makePaymentDTO->getOrderId(),
                    'action' => 'pay',
        ]);

        $result = ['id' => $data['data'], 'sig' => $data['signature']];

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
