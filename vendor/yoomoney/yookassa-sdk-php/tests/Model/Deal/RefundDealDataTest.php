<?php

namespace Tests\YooKassa\Model\Deal;

use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\RefundDealData;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\Deal\SettlementPayoutRefund;

/**
 * @internal
 */
class RefundDealDataTest extends TestCase
{
    /**
     * @dataProvider fromArrayDataProvider
     * @param array $source
     * @param RefundDealData $expected
     * @return void
     */
    public function testFromArray(array $source, RefundDealData $expected): void
    {
        $deal = new RefundDealData($source);
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
                    'refund_settlements' => null,
                ],
            ],
            [
                [
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
                'refund_settlements' => Random::value($invalidData),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function fromArrayDataProvider(): array
    {
        $deal = new RefundDealData();
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
