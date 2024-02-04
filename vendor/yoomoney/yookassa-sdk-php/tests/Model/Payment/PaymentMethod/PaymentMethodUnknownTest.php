<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\AbstractPaymentMethod;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodUnknown;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodUnknownTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): AbstractPaymentMethod
    {
        return new PaymentMethodUnknown();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::UNKNOWN;
    }
}
