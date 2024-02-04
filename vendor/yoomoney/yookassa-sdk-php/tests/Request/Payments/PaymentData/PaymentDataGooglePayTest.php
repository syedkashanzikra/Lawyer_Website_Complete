<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataGooglePay;

/**
 * @internal
 */
class PaymentDataGooglePayTest extends AbstractTestPaymentDataGooglePay
{
    protected function getTestInstance(): PaymentDataGooglePay
    {
        return new PaymentDataGooglePay();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::GOOGLE_PAY;
    }
}
