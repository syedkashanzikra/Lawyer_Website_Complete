<?php

namespace Tests\YooKassa\Model\Deal;

use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\RefundDealInfo;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\Deal\SettlementPayoutRefund;

/**
 * @internal
 */
class RefundDealInfoTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new RefundDealInfo();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new RefundDealInfo();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider fromArrayDataProvider
     */
    public function testFromArray(array $source, RefundDealInfo $expected): void
    {
        $deal = new RefundDealInfo($source);
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
                'refund_settlements' => $this->generateRefundSettlements(),
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
                    'refund_settlements' => null,
                ],
            ],
            [
                [
                    'id' => '',
                    'refund_settlements' => '',
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
                'refund_settlements' => Random::value($invalidData),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function fromArrayDataProvider(): array
    {
        $deal = new RefundDealInfo();
        $deal->setId('dl-285e5ee7-0022-5000-8000-01516a44b147');
        $settlements = [];
        $settlements[] = new SettlementPayoutRefund([
            'type' => SettlementPayoutPaymentType::PAYOUT,
            'amount' => [
                'value' => 123.00,
                'currency' => 'RUB',
            ],
        ]);
        $deal->setRefundSettlements($settlements);

        return [
            [
                [
                    'id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147',
                    'refund_settlements' => [
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

    private function generateRefundSettlements(): array
    {
        $return = [];
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateRefundSettlement();
        }

        return $return;
    }

    private function generateRefundSettlement(): array
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
