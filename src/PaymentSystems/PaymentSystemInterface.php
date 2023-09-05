<?php

namespace YevgeniiV\PsPackage\PaymentSystems;

use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PaymentInfoDTO;

interface PaymentSystemInterface
{
    public function getPaymentInfo(string $paymentId): PaymentInfoDTO;

    public function createPayment(MakePaymentDTO $makePaymentDTO): string;
}
