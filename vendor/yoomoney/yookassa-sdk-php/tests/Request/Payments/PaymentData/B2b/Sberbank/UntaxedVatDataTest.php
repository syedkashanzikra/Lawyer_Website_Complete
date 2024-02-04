<?php

namespace Tests\YooKassa\Request\Payments\PaymentData\B2b\Sberbank;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\UntaxedVatData;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatDataType;

/**
 * @internal
 */
class UntaxedVatDataTest extends TestCase
{
    /**
     * @dataProvider validConstructDataProvider
     */
    public function testConstruct(array $value): void
    {
        $instance = new UntaxedVatData($value);

        self::assertEquals($value['type'], $instance->getType());
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
                    'type' => VatDataType::UNTAXED,
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

    protected function getTestInstance(): UntaxedVatData
    {
        return new UntaxedVatData();
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
