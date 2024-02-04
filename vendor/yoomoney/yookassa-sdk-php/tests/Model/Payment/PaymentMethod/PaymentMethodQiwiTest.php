<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodQiwi;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodQiwiTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodQiwi
    {
        return new PaymentMethodQiwi();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::QIWI;
    }
}
