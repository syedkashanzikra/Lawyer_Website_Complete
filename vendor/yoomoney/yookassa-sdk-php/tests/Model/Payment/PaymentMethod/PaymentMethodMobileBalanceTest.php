<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodMobileBalance;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodMobileBalanceTest extends AbstractTestPaymentMethodPhone
{
    protected function getTestInstance(): PaymentMethodMobileBalance
    {
        return new PaymentMethodMobileBalance();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::MOBILE_BALANCE;
    }
}
