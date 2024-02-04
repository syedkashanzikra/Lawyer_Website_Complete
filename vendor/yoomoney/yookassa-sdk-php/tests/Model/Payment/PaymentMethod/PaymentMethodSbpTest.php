<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodSbp;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodSbpTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodSbp
    {
        return new PaymentMethodSbp();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBP;
    }
}
