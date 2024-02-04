<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodGooglePay;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodGooglePayTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodGooglePay
    {
        return new PaymentMethodGooglePay();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::GOOGLE_PAY;
    }
}
