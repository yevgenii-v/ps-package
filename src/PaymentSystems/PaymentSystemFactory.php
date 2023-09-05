<?php

namespace YevgeniiV\PsPackage\PaymentSystems;

use Srmklive\PayPal\Services\PayPal;
use Stripe\StripeClient;
use Throwable;
use YevgeniiV\PsPackage\Enums\PaymentSystem;
use YevgeniiV\PsPackage\PaymentSystems\DTO\AuthDataDTO;
use YevgeniiV\PsPackage\PaymentSystems\Handlers\Liqpay\LiqpayHandler;
use YevgeniiV\PsPackage\PaymentSystems\Handlers\Paypal\PaypalHandler;
use YevgeniiV\PsPackage\PaymentSystems\Handlers\Stripe\StripeHandler;

class PaymentSystemFactory
{
    /**
     * @throws Throwable
     */
    public function getInstance(PaymentSystem $payments, array $configData): PaymentSystemInterface
    {
        return match ($payments) {
            PaymentSystem::PAYPAL => new PaypalHandler(
                new PayPal(),
                new AuthDataDTO(
                    $configData['paypal']['client_id'],
                    $configData['paypal']['client_secret'],
                    $configData['paypal']['app_id'],
                    $configData['paypal']['mode'],
                )
            ),
            PaymentSystem::STRIPE => new StripeHandler(new StripeClient($configData['stripe']['secret_key'])),
            PaymentSystem::LIQPAY => new LiqpayHandler(
                new AuthDataDTO(
                    $configData['liqpay']['public_key'],
                    $configData['liqpay']['private_key'],
                    null,
                )
            ),
        };
    }
}
