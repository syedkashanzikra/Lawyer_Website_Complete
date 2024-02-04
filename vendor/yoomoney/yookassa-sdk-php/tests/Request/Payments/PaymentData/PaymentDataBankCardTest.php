<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use InvalidArgumentException;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataBankCard;
use YooKassa\Request\Payments\PaymentData\PaymentDataBankCardCard;

/**
 * @internal
 */
class PaymentDataBankCardTest extends AbstractTestPaymentData
{
    /**
     * @dataProvider validCardDataProvider
     */
    public function testGetSetBankCard(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setCard($value);
        if (null === $value || '' === $value || [] === $value) {
            self::assertNull($instance->getCard());
            self::assertNull($instance->card);
        } else {
            if (is_array($value)) {
                $expected = new PaymentDataBankCardCard();
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
        if (null === $value || '' === $value || [] === $value) {
            self::assertNull($instance->getCard());
            self::assertNull($instance->card);
        } else {
            if (is_array($value)) {
                $expected = new PaymentDataBankCardCard();
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
     */
    public function testSetInvalidCard($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCard($value);
    }

    /**
     * @dataProvider invalidCardDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCard($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->card = $value;
    }

    public static function validCardDataProvider()
    {
        return [
            [null],
            [new PaymentDataBankCardCard([
                'number' => Random::str(16, '0123456789'),
                'expiry_year' => (string) Random::int(2023, 2025),
                'expiry_month' => str_pad((string) Random::int(1, 12), 2, '0', STR_PAD_LEFT),
            ])],
            [[]],
            [''],
            [[
                'number' => Random::str(16, '0123456789'),
                'expiry_year' => (string) Random::int(2023, 2025),
                'expiry_month' => str_pad((string) Random::int(1, 12), 2, '0', STR_PAD_LEFT),
            ]],
        ];
    }

    public static function invalidCardDataProvider()
    {
        return [
            [0],
            [1],
            [-1],
            ['5'],
            [true],
            [new stdClass()],
        ];
    }

    protected function getTestInstance(): PaymentDataBankCard
    {
        return new PaymentDataBankCard();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::BANK_CARD;
    }
}
