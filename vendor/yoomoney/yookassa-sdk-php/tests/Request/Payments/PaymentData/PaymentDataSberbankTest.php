<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataSberbank;

/**
 * @internal
 */
class PaymentDataSberbankTest extends AbstractTestPaymentDataPhone
{
    protected function getTestInstance(): PaymentDataSberbank
    {
        return new PaymentDataSberbank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBERBANK;
    }
}
