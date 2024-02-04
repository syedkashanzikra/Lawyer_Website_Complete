<?php

namespace Tests\YooKassa\Request\Refunds;

use DateTime;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Request\Refunds\RefundsRequest;
use YooKassa\Request\Refunds\RefundsRequestSerializer;

/**
 * @internal
 */
class RefundsRequestSerializerTest extends TestCase
{
    private $fieldMap = [
        'paymentId' => 'payment_id',
        'createdAtGte' => 'created_at.gte',
        'createdAtGt' => 'created_at.gt',
        'createdAtLte' => 'created_at.lte',
        'createdAtLt' => 'created_at.lt',
        'status' => 'status',
        'cursor' => 'cursor',
        'limit' => 'limit',
    ];

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSerialize($options): void
    {
        $serializer = new RefundsRequestSerializer();
        $data = $serializer->serialize(RefundsRequest::builder()->build($options));

        $expected = [];
        foreach ($this->fieldMap as $field => $mapped) {
            if (isset($options[$field])) {
                $value = $options[$field];
                if (!empty($value)) {
                    $expected[$mapped] = $value instanceof DateTime ? $value->format(YOOKASSA_DATE) : $value;
                }
            }
        }
        self::assertEquals($expected, $data);
    }

    public function validDataProvider()
    {
        $result = [
            [
                [
                    'accountId' => uniqid('', true),
                ],
            ],
            [
                [
                    'paymentId' => '',
                    'createdAtGte' => '',
                    'createdAtGt' => '',
                    'createdAtLte' => '',
                    'createdAtLt' => '',
                    'status' => '',
                    'cursor' => '',
                    'limit' => 10,
                ],
            ],
        ];
        $statuses = RefundStatus::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'paymentId' => $this->randomString(36),
                'createdAtGte' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'createdAtGt' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'createdAtLte' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'createdAtLt' => (0 === $i ? null : (1 === $i ? '' : date(YOOKASSA_DATE, Random::int(1, time())))),
                'status' => $statuses[Random::int(0, count($statuses) - 1)],
                'cursor' => uniqid('', true),
                'limit' => Random::int(1, RefundsRequest::MAX_LIMIT_VALUE),
            ];
            $result[] = [$request];
        }

        return $result;
    }

    private function randomString($length, $any = true)
    {
        static $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-+_.';

        $result = '';
        for ($i = 0; $i < $length; $i++) {
            if ($any) {
                $char = chr(Random::int(32, 126));
            } else {
                $rnd = Random::int(0, strlen($chars) - 1);
                $char = $chars[$rnd];
            }
            $result .= $char;
        }

        return $result;
    }
}
