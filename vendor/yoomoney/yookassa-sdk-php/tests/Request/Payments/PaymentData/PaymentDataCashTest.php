<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataCash;

/**
 * @internal
 */
class PaymentDataCashTest extends AbstractTestPaymentDataPhone
{
    public function validPhoneDataProvider()
    {
        return [
            ['0123'],
            ['45678'],
            ['901234'],
            ['5678901'],
            ['23456789'],
            ['012345678'],
            ['9012345678'],
            ['90123456789'],
            ['012345678901'],
            ['5678901234567'],
            ['89012345678901'],
            ['234567890123456'],
            [null],
            [''],
        ];
    }

    public function invalidPhoneDataProvider()
    {
        return [
            [true],
            ['2345678901234567'],
        ];
    }

    protected function getTestInstance(): PaymentDataCash
    {
        return new PaymentDataCash();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::CASH;
    }
}
