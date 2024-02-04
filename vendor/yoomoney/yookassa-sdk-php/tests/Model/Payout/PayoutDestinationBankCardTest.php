<?php

namespace Tests\YooKassa\Model\Payout;

use Exception;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\PayoutDestinationBankCard;
use YooKassa\Model\Payout\PayoutDestinationBankCardCard;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Validator\Exceptions\ValidatorParameterException;

/**
 * @internal
 */
class PayoutDestinationBankCardTest extends AbstractTestPayoutDestination
{
    /**
     * @dataProvider validCardDataProvider
     */
    public function testGetSetBankCard(mixed $value): void
    {
        $instance = $this->getTestInstance();

        self::assertNull($instance->getCard());
        self::assertNull($instance->card);

        $instance->setCard($value);
        if (null === $value) {
            self::assertNull($instance->getCard());
            self::assertNull($instance->card);
        } else {
            if (is_array($value)) {
                $expected = new PayoutDestinationBankCardCard();
                foreach ($value as $property => $val) {
                    $expected->offsetSet($property, $val);
                }
            } else {
                $expected = $value;
            }
            self::assertEquals($expected, $instance->getCard());
            self::assertEquals($expected, $instance->card);
        }

        $instance = $this->getTestInstance();
        $instance->card = $value;
        if (null === $value) {
            self::assertNull($instance->getCard());
            self::assertNull($instance->card);
        } else {
            if (is_array($value)) {
                $expected = new PayoutDestinationBankCardCard();
                foreach ($value as $property => $val) {
                    $expected->offsetSet($property, $val);
                }
            } else {
                $expected = $value;
            }
            self::assertEquals($expected, $instance->getCard());
            self::assertEquals($expected, $instance->card);
        }
    }

    /**
     * @dataProvider invalidCardDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidCard(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setCard($value);
    }

    /**
     * @dataProvider invalidCardDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidCard(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->card = $value;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validCardDataProvider(): array
    {
        return [
            [null],
            [new PayoutDestinationBankCardCard([
                'first6' => Random::str(6, '0123456789'),
                'last4' => Random::str(4, '0123456789'),
                'card_type' => Random::value(BankCardType::getValidValues()),
            ])],
            [[
                'first6' => Random::str(6, '0123456789'),
                'last4' => Random::str(4, '0123456789'),
                'card_type' => Random::value(BankCardType::getValidValues()),
                'issuer_country' => 'RU',
                'issuer_name' => 'SberBank',
            ]],
        ];
    }

    /**
     * @return array
     */
    public static function invalidCardDataProvider(): array
    {
        return [
            [[''], EmptyPropertyValueException::class],
            ['5', ValidatorParameterException::class],
            [[
                'first6' => Random::str(100),
                'last4'  => Random::str(100),
                'card_type'  => Random::str(100),
            ], InvalidPropertyValueException::class],
            [new stdClass(), InvalidPropertyValueTypeException::class],
            [[new stdClass()], EmptyPropertyValueException::class],
        ];
    }

    /**
     * @return PayoutDestinationBankCard
     */
    protected function getTestInstance(): PayoutDestinationBankCard
    {
        return new PayoutDestinationBankCard();
    }

    /**
     * @return string
     */
    protected function getExpectedType(): string
    {
        return PaymentMethodType::BANK_CARD;
    }
}
