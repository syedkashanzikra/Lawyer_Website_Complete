<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\AbstractPaymentData;
use YooKassa\Request\Payments\PaymentData\PaymentDataSberLoan;

/**
 * @internal
 */
class PaymentDataSberLoanTest extends AbstractTestPaymentData
{
    protected function getTestInstance(): AbstractPaymentData
    {
        return new PaymentDataSberLoan();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBER_LOAN;
    }
}
