<?php

namespace Tests\YooKassa\Request\Payouts\PayoutDestinationData;

use DateTime;
use InvalidArgumentException;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataBankCard;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataBankCardCard;

/**
 * @internal
 */
class PayoutDestinationDataBankCardTest extends AbstractTestPayoutDestinationData
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
                $expected = new PayoutDestinationDataBankCardCard();
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
                $expected = new PayoutDestinationDataBankCardCard($value);
            } else {
                $expected = $value;
            }
            self::assertEquals($expected, $instance->getCard());
            self::assertEquals($expected, $instance->card);

            self::assertEquals($expected['number'], $instance->getCard()->getNumber());
            self::assertEquals($expected['number'], $instance->card->number);
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

    public static function validCardDataProvider(): array
    {
        return [
            [null],
            [new PayoutDestinationDataBankCardCard(['number' => Random::str(16, '0123456789')])],
            [[
                'number' => Random::str(16, '0123456789'),
            ]],
        ];
    }

    public static function invalidCardDataProvider(): array
    {
        return [
            [0],
            [1],
            [-1],
            ['5'],
            [true],
            [new stdClass()],
            [new DateTime()],
            [['number' => '']],
            [['number' => null]],
            [['number' => Random::str(16)]],
        ];
    }

    protected function getTestInstance(): PayoutDestinationDataBankCard
    {
        return new PayoutDestinationDataBankCard();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::BANK_CARD;
    }
}
