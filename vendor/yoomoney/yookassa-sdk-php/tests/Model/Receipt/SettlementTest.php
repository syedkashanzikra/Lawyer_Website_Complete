<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\Settlement;
use YooKassa\Model\Receipt\SettlementType;

/**
 * @internal
 */
class SettlementTest extends TestCase
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
        $this->expectException(InvalidArgumentException::class);
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
                'type' => Random::value(SettlementType::getValidValues()),
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

    public static function validAmountDataProvider()
    {
        return [
            [
                new MonetaryAmount(
                    Random::int(1, 100),
                    Random::value(CurrencyCode::getValidValues())
                ),
            ],
            [
                new MonetaryAmount(),
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
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setAmount($value);
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->amount = $value;
    }

    public static function invalidAmountDataProvider()
    {
        return [
            [null],
            [''],
            [1.0],
            [1],
            [true],
            [false],
            [new stdClass()],
        ];
    }

    public static function invalidTypeDataProvider()
    {
        return [
            [''],
            [1.0],
            [1],
            [true],
            [false],
            [Random::str(1, 10)],
        ];
    }

    protected function getTestInstance()
    {
        return new Settlement();
    }
}
