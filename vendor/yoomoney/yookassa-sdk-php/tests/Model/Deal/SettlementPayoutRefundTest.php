<?php

namespace Tests\YooKassa\Model\Deal;

use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\Deal\SettlementPayoutRefund;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Validator\Exceptions\InvalidPropertyException;

/**
 * @internal
 */
class SettlementPayoutRefundTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testFromArray(array $value): void
    {
        $instance = $this->getTestInstance();

        $instance->fromArray($value);

        self::assertSame($value['type'], $instance->getType());
        self::assertSame($value['type'], $instance->type);
        self::assertSame($value['amount'], $instance->getAmount()->jsonSerialize());
        self::assertSame($value['amount'], $instance->amount->jsonSerialize());

        self::assertSame($value, $instance->jsonSerialize());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetType(array $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setType($value['type']);
        self::assertSame($value['type'], $instance->getType());
        self::assertSame($value['type'], $instance->type);
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidType($value): void
    {
        $this->expectException(InvalidPropertyException::class);
        $this->getTestInstance()->setType($value);
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [
                'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                'amount' => [
                    'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
            ];
        }

        return [$result];
    }

    /**
     * @dataProvider validAmountDataProvider
     */
    public function testGetSetAmount(AmountInterface $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAmount($value);
        self::assertSame($value, $instance->getAmount());
        self::assertSame($value, $instance->amount);
    }

    /**
     * @dataProvider validAmountDataProvider
     */
    public function testSetterAmount(AmountInterface $value): void
    {
        $instance = $this->getTestInstance();
        $instance->amount = $value;
        self::assertSame($value, $instance->getAmount());
        self::assertSame($value, $instance->amount);
    }

    public static function validAmountDataProvider(): array
    {
        return [
            [
                new MonetaryAmount(
                    Random::int(1, 100),
                    Random::value(CurrencyCode::getValidValues())
                ),
            ],
            [
                new MonetaryAmount(['value' => Random::float(0.01, 99.99)]),
            ],
        ];
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmount($value): void
    {
        $this->expectException(InvalidPropertyException::class);
        $this->getTestInstance()->setAmount($value);
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAmount($value): void
    {
        $this->expectException(InvalidPropertyException::class);
        $this->getTestInstance()->amount = $value;
    }

    public static function invalidAmountDataProvider(): array
    {
        return [
            [null],
            [''],
            [false],
            [new stdClass()],
        ];
    }

    public static function invalidTypeDataProvider(): array
    {
        return [
            [null],
            [''],
            [false],
            [Random::str(1, 10)],
        ];
    }

    protected function getTestInstance(): SettlementPayoutRefund
    {
        return new SettlementPayoutRefund();
    }
}
