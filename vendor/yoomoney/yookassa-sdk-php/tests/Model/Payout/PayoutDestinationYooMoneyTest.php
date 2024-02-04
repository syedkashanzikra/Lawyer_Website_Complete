<?php

namespace Tests\YooKassa\Model\Payout;

use Exception;
use stdClass;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\PayoutDestinationYooMoney;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class PayoutDestinationYooMoneyTest extends AbstractTestPayoutDestination
{
    /**
     * @dataProvider validAccountNumberDataProvider
     */
    public function testGetSetAccountNumber(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAccountNumber($value);
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
     * @param string $exceptionClassName
     */
    public function testSetInvalidAccountNumber(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setAccountNumber($value);
    }

    /**
     * @dataProvider invalidAccountNumberDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidAccountNumber(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->account_number = $value;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validAccountNumberDataProvider(): array
    {
        return [
            [1234567894560],
            ['0123456789456'],
            [Random::str(PayoutDestinationYooMoney::MIN_LENGTH_ACCOUNT_NUMBER, PayoutDestinationYooMoney::MAX_LENGTH_ACCOUNT_NUMBER, '0123456789')],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidAccountNumberDataProvider(): array
    {
        return [
             ['', EmptyPropertyValueException::class],
             [Random::str(PayoutDestinationYooMoney::MIN_LENGTH_ACCOUNT_NUMBER - 1), InvalidPropertyValueException::class],
             [Random::str(PayoutDestinationYooMoney::MAX_LENGTH_ACCOUNT_NUMBER + 1), InvalidPropertyValueException::class],
             [true, InvalidPropertyValueException::class],
             [[], TypeError::class],
             [new stdClass(), TypeError::class],
        ];
    }

    /**
     * @return PayoutDestinationYooMoney
     */
    protected function getTestInstance(): PayoutDestinationYooMoney
    {
        return new PayoutDestinationYooMoney();
    }

    /**
     * @return string
     */
    protected function getExpectedType(): string
    {
        return PaymentMethodType::YOO_MONEY;
    }
}
