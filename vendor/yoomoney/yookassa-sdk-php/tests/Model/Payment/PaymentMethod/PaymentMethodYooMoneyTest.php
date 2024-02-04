<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodYooMoney;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodYooMoneyTest extends AbstractTestPaymentMethod
{
    /**
     * @dataProvider validAccountNumberDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetAccountNumber($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAccountNumber($value);
        self::assertEquals($value, $instance->getAccountNumber());
        self::assertEquals($value, $instance->accountNumber);
        self::assertEquals($value, $instance->account_number);

        $instance = $this->getTestInstance();
        $instance->accountNumber = $value;
        self::assertEquals($value, $instance->getAccountNumber());
        self::assertEquals($value, $instance->accountNumber);
        self::assertEquals($value, $instance->account_number);

        $instance = $this->getTestInstance();
        $instance->account_number = $value;
        self::assertEquals($value, $instance->getAccountNumber());
        self::assertEquals($value, $instance->accountNumber);
        self::assertEquals($value, $instance->account_number);
    }

    /**
     * @dataProvider invalidAccountNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAccountNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setAccountNumber($value);
    }

    /**
     * @dataProvider invalidAccountNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAccountNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->accountNumber = $value;
    }

    /**
     * @dataProvider invalidAccountNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeAccountNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->account_number = $value;
    }

    public static function validAccountNumberDataProvider()
    {
        return [
            [Random::str(11, '0123456789')],
            [Random::str(12, '0123456789')],
            [Random::str(13, '0123456789')],
            [Random::str(31, '0123456789')],
            [Random::str(32, '0123456789')],
            [Random::str(33, '0123456789')],
        ];
    }

    public static function invalidAccountNumberDataProvider()
    {
        return [
            [true],
            [Random::str(10, '0123456789')],
            [Random::str(34, '0123456789')],
        ];
    }

    protected function getTestInstance(): PaymentMethodYooMoney
    {
        return new PaymentMethodYooMoney();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::YOO_MONEY;
    }
}
