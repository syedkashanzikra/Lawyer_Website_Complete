<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodCash;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodCashTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodCash
    {
        return new PaymentMethodCash();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::CASH;
    }
}
