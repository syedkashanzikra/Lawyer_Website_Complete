<?php

namespace Tests\YooKassa\Request\Payouts\PayoutDestinationData;

use InvalidArgumentException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataYooMoney;

/**
 * @internal
 */
class PayoutDestinationDataYooMoneyTest extends AbstractTestPayoutDestinationData
{
    /**
     * @dataProvider validAccountNumberDataProvider
     */
    public function testGetSetAccountNumber(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAccountNumber($value);
        if (null === $value || '' === $value || [] === $value) {
            self::assertNull($instance->getAccountNumber());
            self::assertNull($instance->accountNumber);
            self::assertNull($instance->account_number);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getAccountNumber());
            self::assertEquals($expected, $instance->accountNumber);
            self::assertEquals($expected, $instance->account_number);
        }

        $instance = $this->getTestInstance();
        $instance->account_number = $value;
        if (null === $value || '' === $value || [] === $value) {
            self::assertNull($instance->getAccountNumber());
            self::assertNull($instance->accountNumber);
            self::assertNull($instance->account_number);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getAccountNumber());
            self::assertEquals($expected, $instance->accountNumber);
            self::assertEquals($expected, $instance->account_number);
        }
    }

    /**
     * @dataProvider invalidAccountNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAccountNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setAccountNumber($value);
    }

    /**
     * @dataProvider invalidAccountNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAccountNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->account_number = $value;
    }

    public static function validAccountNumberDataProvider(): array
    {
        return [
            [1234567894560],
            ['0123456789456'],
            [Random::str(11, 33, '0123456789')],
        ];
    }

    public static function invalidAccountNumberDataProvider(): array
    {
        return [
            [0],
            [''],
            [null],
            [Random::str(34, 50, '0123456789')],
            [true],
            [Random::str(1, 10, '0123456789')],
        ];
    }

    protected function getTestInstance(): PayoutDestinationDataYooMoney
    {
        return new PayoutDestinationDataYooMoney();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::YOO_MONEY;
    }
}
