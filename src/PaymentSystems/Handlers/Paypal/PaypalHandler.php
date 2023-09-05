<?php

namespace YevgeniiV\PsPackage\PaymentSystems\Handlers\Paypal;

use Srmklive\PayPal\Services\PayPal;
use Throwable;
use YevgeniiV\PsPackage\PaymentSystems\DTO\AuthDataDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\MakePaymentDTO;
use YevgeniiV\PsPackage\PaymentSystems\DTO\PaymentInfoDTO;
use YevgeniiV\PsPackage\PaymentSystems\PaymentSystemInterface;

class PaypalHandler implements PaymentSystemInterface
{
    /**
     * @throws Throwable
     */
    public function __construct(
        protected PayPal $payPalClient,
        AuthDataDTO $authData
    ) {
        $this->payPalClient->setApiCredentials([
            'mode' => $authData->isSandbox() === false ? 'live' : 'sandbox',
            // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id' => $authData->getPublic(),
                'client_secret' => $authData->getPrivate(),
                'app_id' => $authData->getId(),
            ],
            'live' => [
                'client_id' => $authData->getPublic(),
                'client_secret' => $authData->getPrivate(),
                'app_id' => $authData->getId(),
            ],
            'payment_action' => 'Sale',
            // Can only be 'Sale', 'Authorization' or 'Order'
            'currency' => 'USD',
            'notify_url' => '',
            // Change this accordingly for your application.
            'locale' => 'en_US',
            // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl' => true,
            // Validate SSL when creating api client.
        ]);
        $this->payPalClient->getAccessToken();
    }

    /**
     * @throws Throwable
     */
    public function getPaymentInfo(string $paymentId): PaymentInfoDTO
    {
        return (new GetPaymentInfoService())->handle($this->payPalClient, $paymentId);
    }

    /**
     * @throws Throwable
     */
    public function createPayment(MakePaymentDTO $makePaymentDTO): string
    {
        return (new CreatePaymentService())->handle($this->payPalClient, $makePaymentDTO);
    }
}
