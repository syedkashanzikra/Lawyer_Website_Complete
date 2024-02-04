<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodWebmoney;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodWebmoneyTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodWebmoney
    {
        return new PaymentMethodWebmoney();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::WEBMONEY;
    }
}
