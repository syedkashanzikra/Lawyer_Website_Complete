<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\FraudData;

/**
 * @internal
 */
class FraudDataTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetToppedUpPhone(array $options): void
    {
        $instance = new FraudData();

        $instance->setToppedUpPhone($options['topped_up_phone']);
        self::assertEquals($options['topped_up_phone'], $instance->getToppedUpPhone());
        self::assertEquals($options['topped_up_phone'], $instance->topped_up_phone);

        $instance = new FraudData();
        $instance->topped_up_phone = $options['topped_up_phone'];
        self::assertEquals($options['topped_up_phone'], $instance->getToppedUpPhone());
        self::assertEquals($options['topped_up_phone'], $instance->topped_up_phone);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @throws Exception
     */
    public function testSetInvalidToppedUpPhone($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new FraudData();
        $instance->setToppedUpPhone($value);
    }

    public static function validDataProvider()
    {
        $result = [];
        $result[] = [['topped_up_phone' => null]];
        $result[] = [['topped_up_phone' => '']];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'topped_up_phone' => Random::str(4, 15, '0123456789'),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function invalidDataProvider()
    {
        return [
            [Random::str(1, 3, '0123456789')],
            [Random::str(16, 30, '0123456789')],
            [Random::str(4, 16)],
        ];
    }
}
