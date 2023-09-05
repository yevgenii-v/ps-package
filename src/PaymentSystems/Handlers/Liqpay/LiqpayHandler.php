<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Liqpay;

use YevgeniiV\PsPackage\PaymentSystems\DTO\AuthDataDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PaymentInfoDTO;
use YevgeniiV\PsPackage\PaymentSystems\PaymentSystemInterface;

class LiqpayHandler implements PaymentSystemInterface
{
    protected Liqpay $liqpay;

    public function __construct(AuthDataDTO $authDataDTO)
    {
        $this->liqpay = new Liqpay($authDataDTO->getPublic(), $authDataDTO->getPrivate());
    }

    public function getPaymentInfo(string $paymentId): PaymentInfoDTO
    {
        return (new GetPaymentInfoService())->handle($this->liqpay, $paymentId);
    }

    public function createPayment(MakePaymentDTO $makePaymentDTO): string
    {
        return (new CreatePaymentService())->handle($this->liqpay, $makePaymentDTO);
    }
}
