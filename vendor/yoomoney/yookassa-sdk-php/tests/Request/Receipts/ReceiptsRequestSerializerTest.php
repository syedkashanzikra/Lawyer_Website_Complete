<?php

namespace Tests\YooKassa\Request\Receipts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Request\Receipts\ReceiptsRequest;
use YooKassa\Request\Receipts\ReceiptsRequestSerializer;

/**
 * @internal
 */
class ReceiptsRequestSerializerTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testSerialize($value): void
    {
        $serializer = new ReceiptsRequestSerializer();
        $instance = ReceiptsRequest::builder()->build($value);
        $data = $serializer->serialize($instance);

        $expected = [];

        if (!empty($value)) {
            $expected = [
                'payment_id' => $value['paymentId'],
                'refund_id' => $value['refundId'],
                'status' => $value['status'],
                'limit' => $value['limit'],
                'cursor' => $value['cursor'],
            ];

            if (!empty($value['createdAtLt'])) {
                $expected['created_at.lt'] = $value['createdAtLt'];
            }

            if (!empty($value['createdAtGt'])) {
                $expected['created_at.gt'] = $value['createdAtGt'];
            }

            if (!empty($value['createdAtLte'])) {
                $expected['created_at.lte'] = $value['createdAtLte'];
            }

            if (!empty($value['createdAtGte'])) {
                $expected['created_at.gte'] = $value['createdAtGte'];
            }
        }

        self::assertEquals($expected, $data);
    }

    public static function validDataProvider()
    {
        $result = [
            [
                [
                    'paymentId' => '216749da-000f-50be-b000-096747fad91e',
                    'refundId' => '216749f7-0016-50be-b000-078d43a63ae4',
                    'status' => RefundStatus::SUCCEEDED,
                    'limit' => 100,
                    'cursor' => '37a5c87d-3984-51e8-a7f3-8de646d39ec15',
                    'createdAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'createdAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'createdAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'createdAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                ],
            ],
            [
                [],
            ],
        ];
        for ($i = 0; $i < 8; $i++) {
            $receipts = [
                'paymentId' => Random::str(36),
                'refundId' => Random::str(36),
                'createdAtGte' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'createdAtGt' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'createdAtLte' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'createdAtLt' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'status' => Random::value(ReceiptRegistrationStatus::getValidValues()),
                'cursor' => uniqid('', true),
                'limit' => Random::int(1, ReceiptsRequest::MAX_LIMIT_VALUE),
            ];
            $result[] = [$receipts];
        }

        return $result;
    }
}
