<?php

namespace Tests\YooKassa\Request\Payouts;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payouts\PayoutPersonalData;

/**
 * @internal
 */
class PayoutPersonalDataTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetId(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    public static function validDataProvider(): array
    {
        $result = [];

        for ($i = 0; $i < 10; $i++) {
            $deal = [
                'id' => Random::str(36, 50),
            ];
            $result[] = [$deal];
        }

        return $result;
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PayoutPersonalData();
        $instance->setId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PayoutPersonalData();
        $instance->id = $value;
    }

    public static function invalidIdDataProvider(): array
    {
        return [
            [false],
            [true],
            [Random::str(1, 35)],
            [Random::str(51, 60)],
        ];
    }

    /**
     * @param mixed $options
     */
    protected function getTestInstance($options): PayoutPersonalData
    {
        return new PayoutPersonalData($options);
    }
}
