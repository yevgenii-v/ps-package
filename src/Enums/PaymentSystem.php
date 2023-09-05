<?php

namespace YevgeniiV\PsPackage\Enums;

enum PaymentSystem: int
{
    case PAYPAL = 1;
    case STRIPE = 2;
    case LIQPAY = 3;
}
