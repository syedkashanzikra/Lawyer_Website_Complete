<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataQiwi;

/**
 * @internal
 */
class PaymentDataQiwiTest extends AbstractTestPaymentDataPhone
{
    protected function getTestInstance(): PaymentDataQiwi
    {
        return new PaymentDataQiwi();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::QIWI;
    }
}
