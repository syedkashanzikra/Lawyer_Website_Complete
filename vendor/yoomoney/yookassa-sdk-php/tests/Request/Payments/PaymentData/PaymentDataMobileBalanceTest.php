<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataMobileBalance;

/**
 * @internal
 */
class PaymentDataMobileBalanceTest extends AbstractTestPaymentDataPhone
{
    protected function getTestInstance(): PaymentDataMobileBalance
    {
        return new PaymentDataMobileBalance();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::MOBILE_BALANCE;
    }
}
