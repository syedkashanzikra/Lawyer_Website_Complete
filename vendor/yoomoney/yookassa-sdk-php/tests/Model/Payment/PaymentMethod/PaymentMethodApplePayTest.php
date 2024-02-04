<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodApplePay;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodApplePayTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodApplePay
    {
        return new PaymentMethodApplePay();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::APPLE_PAY;
    }
}
