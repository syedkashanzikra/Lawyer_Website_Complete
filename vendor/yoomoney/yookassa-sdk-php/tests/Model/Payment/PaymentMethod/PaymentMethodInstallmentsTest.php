<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodInstallments;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodInstallmentsTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodInstallments
    {
        return new PaymentMethodInstallments();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::INSTALLMENTS;
    }
}
