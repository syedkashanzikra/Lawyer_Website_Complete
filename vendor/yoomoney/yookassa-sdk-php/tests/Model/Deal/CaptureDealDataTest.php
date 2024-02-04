<?php

namespace Tests\YooKassa\Model\Deal;

use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\CaptureDealData;
use YooKassa\Model\Deal\SettlementPayoutPayment;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;

/**
 * @internal
 */
class CaptureDealDataTest extends TestCase
{
    /**
     * @dataProvider fromArrayDataProvider
     */
    public function testFromArray(array $source, CaptureDealData $expected): void
    {
        $deal = new CaptureDealData($source);
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
                    'settlements' => null,
                ],
            ],
            [
                [
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
                'settlements' => Random::value($invalidData),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function fromArrayDataProvider(): array
    {
        $deal = new CaptureDealData();
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
