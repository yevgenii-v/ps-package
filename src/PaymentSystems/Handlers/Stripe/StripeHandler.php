<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Stripe;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PaymentInfoDTO;
use YevgeniiV\PsPackage\PaymentSystems\PaymentSystemInterface;

class StripeHandler implements PaymentSystemInterface
{
    public function __construct(
        protected StripeClient $stripe
    ) {
    }

    /**
     * @throws ApiErrorException
     */
    public function getPaymentInfo(string $paymentId): PaymentInfoDTO
    {
        return (new GetPaymentInfoService())->handle($this->stripe, $paymentId);
    }

    /**
     * @throws ApiErrorException
     */
    public function createPayment(MakePaymentDTO $makePaymentDTO): string
    {
        return (new CreatePaymentService())->handle($this->stripe, $makePaymentDTO);
    }
}
