<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodSberbank;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodSberbankTest extends PaymentMethodBankCardTest
{
    /**
     * @dataProvider validPhoneDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetPhone($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setPhone($value);
        self::assertEquals($value, $instance->getPhone());
        self::assertEquals($value, $instance->phone);

        $instance = $this->getTestInstance();
        $instance->phone = $value;
        self::assertEquals($value, $instance->getPhone());
        self::assertEquals($value, $instance->phone);
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPhone($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setPhone($value);
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPhone($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->phone = $value;
    }

    public static function validPhoneDataProvider()
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
        ];
    }

    public static function invalidPhoneDataProvider()
    {
        return [
            [null],
            [''],
            [true],
            [false],
            ['2345678901234567'],
        ];
    }

    protected function getTestInstance(): PaymentMethodSberbank
    {
        return new PaymentMethodSberbank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBERBANK;
    }
}
