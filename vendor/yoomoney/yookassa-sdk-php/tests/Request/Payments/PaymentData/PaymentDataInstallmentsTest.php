<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataInstallments;

/**
 * @internal
 */
class PaymentDataInstallmentsTest extends AbstractTestPaymentData
{
    protected function getTestInstance(): PaymentDataInstallments
    {
        return new PaymentDataInstallments();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::INSTALLMENTS;
    }
}
