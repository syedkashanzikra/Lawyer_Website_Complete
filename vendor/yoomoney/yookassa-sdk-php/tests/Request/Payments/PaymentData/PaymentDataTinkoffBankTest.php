<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\AbstractPaymentData;
use YooKassa\Request\Payments\PaymentData\PaymentDataTinkoffBank;

/**
 * @internal
 */
class PaymentDataTinkoffBankTest extends AbstractTestPaymentData
{
    protected function getTestInstance(): AbstractPaymentData
    {
        return new PaymentDataTinkoffBank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::TINKOFF_BANK;
    }
}
