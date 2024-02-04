<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataSbp;

/**
 * @internal
 */
class PaymentDataSbpTest extends AbstractTestPaymentData
{
    protected function getTestInstance(): PaymentDataSbp
    {
        return new PaymentDataSbp();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBP;
    }
}
