<?php

namespace Tests\YooKassa\Request\Payments\PaymentData\B2b\Sberbank;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\MixedVatData;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatDataType;

/**
 * @internal
 */
class MixedVatDataTest extends TestCase
{
    /**
     * @dataProvider validConstructDataProvider
     */
    public function testConstruct(array $value): void
    {
        $instance = new MixedVatData($value);

        self::assertEquals($value['type'], $instance->getType());
        self::assertEquals($value['amount']->getValue(), $instance->getAmount()->getValue());
    }

    /**
     * @dataProvider validTypeDataProvider
     */
    public function testGetSetType(string $value): void
    {
        $this->getAndSetTest($value, 'type');
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
    public static function validConstructDataProvider(): array
    {
        return [
            [
                [
                    'type' => VatDataType::MIXED,
                    'amount' => new MonetaryAmount(Random::int(1, 1000)),
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public static function validTypeDataProvider(): array
    {
        return [
            [VatDataType::CALCULATED],
            [VatDataType::UNTAXED],
            [VatDataType::MIXED],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidTypeDataProvider(): array
    {
        return [
            [''],
            [null],
            [0],
            [1],
            [-1],
            [Random::str(20)],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetAmount($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAmount($value);
        if (is_array($value)) {
            self::assertSame($value['value'], (int) $instance->getAmount()->getValue());
            self::assertSame($value['currency'], $instance->amount->getCurrency());
        } else {
            self::assertSame($value, $instance->getAmount());
            self::assertSame($value, $instance->amount);
        }

        $instance = $this->getTestInstance();

        $instance->amount = $value;
        if (is_array($value)) {
            self::assertSame($value['value'], (int) $instance->getAmount()->getValue());
            self::assertSame($value['currency'], $instance->amount->getCurrency());
        } else {
            self::assertSame($value, $instance->getAmount());
            self::assertSame($value, $instance->amount);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setAmount($value);
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        return [
            [
                [
                    'value' => Random::int(1, 1000),
                    'currency' => CurrencyCode::EUR,
                ],
            ],
            [new MonetaryAmount(Random::int(1, 10000), CurrencyCode::RUB)],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidDataProvider(): array
    {
        return [
            [''],
            [0],
            [1],
            [-1],
            [new stdClass()],
            [Random::str(20)],
        ];
    }

    protected function getTestInstance(): MixedVatData
    {
        return new MixedVatData();
    }

    /**
     * @param null $snakeCase
     * @param mixed $value
     */
    protected function getAndSetTest($value, string $property, $snakeCase = null): void
    {
        $getter = 'get' . ucfirst($property);
        $setter = 'set' . ucfirst($property);

        $instance = $this->getTestInstance();

        $instance->{$setter}($value);

        self::assertEquals($value, $instance->{$getter}());
        self::assertEquals($value, $instance->{$property});
        if (null !== $snakeCase) {
            self::assertEquals($value, $instance->{$snakeCase});
        }

        $instance = $this->getTestInstance();

        $instance->{$property} = $value;

        self::assertEquals($value, $instance->{$getter}());
        self::assertEquals($value, $instance->{$property});
        if (null !== $snakeCase) {
            self::assertEquals($value, $instance->{$snakeCase});
        }

        if (null !== $snakeCase) {
            $instance = $this->getTestInstance();

            $instance->{$snakeCase} = $value;

            self::assertEquals($value, $instance->{$getter}());
            self::assertEquals($value, $instance->{$property});
            self::assertEquals($value, $instance->{$snakeCase});
        }
    }
}
