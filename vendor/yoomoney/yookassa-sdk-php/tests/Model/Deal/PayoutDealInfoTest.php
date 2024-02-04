<?php

namespace Tests\YooKassa\Model\Deal;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\PayoutDealInfo;

/**
 * @internal
 */
class PayoutDealInfoTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new PayoutDealInfo();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new PayoutDealInfo();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider fromArrayDataProvider
     */
    public function testFromArray(array $source, PayoutDealInfo $expected): void
    {
        $deal = new PayoutDealInfo($source);
        $dealArray = $expected->toArray();

        if (!empty($source)) {
            foreach ($source as $property => $value) {
                self::assertEquals($value, $dealArray[$property]);
            }
        }
    }

    public static function validDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str(36, 50),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public function invalidDataProvider(): array
    {
        $result = [
            [
                [
                    'id' => null,
                ],
            ],
            [
                [
                    'id' => '',
                ],
            ],
        ];

        for ($i = 0; $i < 9; $i++) {
            $payment = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(51, 64)),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function fromArrayDataProvider(): array
    {
        $customer = new PayoutDealInfo();
        $customer->setId('dl-285e5ee7-0022-5000-8000-01516a44b147');

        return [
            [
                [
                    'id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147',
                ],
                $customer,
            ],
        ];
    }
}
