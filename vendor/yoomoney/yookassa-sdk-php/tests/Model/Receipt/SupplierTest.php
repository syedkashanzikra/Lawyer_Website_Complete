<?php

namespace Tests\YooKassa\Model\Receipt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\Supplier;

/**
 * @internal
 */
class SupplierTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testSetGetInn($value): void
    {
        $instance = new Supplier();

        $instance->setInn($value['inn']);
        self::assertEquals($value['inn'], $instance->getInn());
    }

    /**
     * @dataProvider invalidInnDataTest
     *
     * @param mixed $value
     */
    public function testInvalidInn($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Supplier();
        $instance->setInn($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testSetGetName($value): void
    {
        $instance = new Supplier();
        $instance->setName($value['name']);
        self::assertEquals($value['name'], $instance->getName());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testSetGetPhone($value): void
    {
        $instance = new Supplier();

        $instance->setPhone($value['phone']);
        self::assertEquals($value['phone'], $instance->getPhone());
    }

    public static function validDataProvider()
    {
        $result = [
            [
                [
                    'name' => null,
                    'phone' => null,
                    'inn' => null,
                ],
            ],
            [
                [
                    'name' => 'John Doe',
                    'inn' => '6321341814',
                    'phone' => '79000000000',
                ],
            ],
        ];

        for ($i = 0; $i < 7; $i++) {
            $test = [
                'name' => Random::str(1, 150),
                'inn' => Random::str(12, 12, '1234567890'),
                'phone' => Random::str(10, 10, '1234567890'),
            ];

            $result[] = [$test];
        }

        return $result;
    }

    public static function invalidInnDataTest()
    {
        return [
            ['test'],
            [true],
        ];
    }
}
