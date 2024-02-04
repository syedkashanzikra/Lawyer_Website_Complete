<?php

namespace Tests\YooKassa\Model\Deal;

use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\PaymentDealInfo;
use YooKassa\Model\Deal\SettlementPayoutPayment;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;

/**
 * @internal
 */
class PaymentDealInfoTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new PaymentDealInfo();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new PaymentDealInfo();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider fromArrayDataProvider
     */
    public function testFromArray(array $source, PaymentDealInfo $expected): void
    {
        $deal = new PaymentDealInfo($source);
        $dealArray = $expected->toArray();

        if (!empty($deal)) {
            foreach ($deal->toArray() as $property => $value) {
                self::assertEquals($value, $dealArray[$property]);
            }
        }
    }

    public function validDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str(36, 50),
                'settlements' => $this->generateSettlements(),
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
                    'settlements' => null,
                ],
            ],
            [
                [
                    'id' => '',
                    'settlements' => '',
                ],
            ],
        ];
        $invalidData = [
            [null],
            [''],
            [new stdClass()],
            ['invalid_value'],
            [0],
            [3234],
            [true],
            [false],
            [0.43],
        ];
        for ($i = 0; $i < 9; $i++) {
            $payment = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(51, 64)),
                'settlements' => Random::value($invalidData),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function fromArrayDataProvider(): array
    {
        $deal = new PaymentDealInfo();
        $deal->setId('dl-285e5ee7-0022-5000-8000-01516a44b147');
        $settlements = [];
        $settlements[] = new SettlementPayoutPayment([
            'type' => SettlementPayoutPaymentType::PAYOUT,
            'amount' => [
                'value' => 123.00,
                'currency' => 'RUB',
            ],
        ]);
        $deal->setSettlements($settlements);

        return [
            [
                [
                    'id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147',
                    'settlements' => [
                        [
                            'type' => SettlementPayoutPaymentType::PAYOUT,
                            'amount' => [
                                'value' => 123.00,
                                'currency' => 'RUB',
                            ],
                        ],
                    ],
                ],
                $deal,
            ],
        ];
    }

    private function generateSettlements(): array
    {
        $return = [];
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateSettlement();
        }

        return $return;
    }

    private function generateSettlement(): array
    {
        return [
            'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
            'amount' => [
                'value' => round(Random::float(1.00, 100.00), 2),
                'currency' => 'RUB',
            ],
        ];
    }
}
