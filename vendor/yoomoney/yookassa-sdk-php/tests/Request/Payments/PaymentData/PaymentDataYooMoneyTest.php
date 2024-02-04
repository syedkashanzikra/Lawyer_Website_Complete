<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataYooMoney;

/**
 * @internal
 */
class PaymentDataYooMoneyTest extends AbstractTestPaymentData
{
    protected function getTestInstance(): PaymentDataYooMoney
    {
        return new PaymentDataYooMoney();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::YOO_MONEY;
    }
}
