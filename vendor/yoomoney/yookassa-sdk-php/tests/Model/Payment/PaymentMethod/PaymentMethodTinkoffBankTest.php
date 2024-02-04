<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\AbstractPaymentMethod;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodTinkoffBank;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodTinkoffBankTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): AbstractPaymentMethod
    {
        return new PaymentMethodTinkoffBank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::TINKOFF_BANK;
    }
}
