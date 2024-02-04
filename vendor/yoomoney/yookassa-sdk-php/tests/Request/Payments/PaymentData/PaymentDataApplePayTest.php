<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataApplePay;

/**
 * @internal
 */
class PaymentDataApplePayTest extends AbstractTestPaymentDataApplePay
{
    protected function getTestInstance(): PaymentDataApplePay
    {
        return new PaymentDataApplePay();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::APPLE_PAY;
    }
}
