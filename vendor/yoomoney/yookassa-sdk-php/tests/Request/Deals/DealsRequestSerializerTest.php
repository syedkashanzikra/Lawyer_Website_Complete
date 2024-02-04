<?php

namespace Tests\YooKassa\Request\Deals;

use DateTime;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\DealStatus;
use YooKassa\Model\Deal\SafeDeal;
use YooKassa\Request\Deals\DealsRequest;
use YooKassa\Request\Deals\DealsRequestSerializer;

/**
 * @internal
 */
class DealsRequestSerializerTest extends TestCase
{
    private $fieldMap = [
        'createdAtGte' => 'created_at.gte',
        'createdAtGt' => 'created_at.gt',
        'createdAtLte' => 'created_at.lte',
        'createdAtLt' => 'created_at.lt',
        'expiresAtGte' => 'expires_at.gte',
        'expiresAtGt' => 'expires_at.gt',
        'expiresAtLte' => 'expires_at.lte',
        'expiresAtLt' => 'expires_at.lt',
        'status' => 'status',
        'fullTextSearch' => 'full_text_search',
        'limit' => 'limit',
        'cursor' => 'cursor',
    ];

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSerialize($options): void
    {
        $serializer = new DealsRequestSerializer();
        $data = $serializer->serialize(DealsRequest::builder()->build($options));

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

    public function validDataProvider(): array
    {
        $result = [
            [
                [
                    'limit' => Random::int(1, DealsRequest::MAX_LIMIT_VALUE),
                ],
            ],
            [
                [
                    'fullTextSearch' => '',
                    'status' => '',
                    'limit' => 1,
                    'cursor' => '',
                ],
            ],
        ];
        $statuses = DealStatus::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'createdAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expiresAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expiresAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expiresAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expiresAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'fullTextSearch' => Random::str(DealsRequest::MIN_LENGTH_DESCRIPTION, SafeDeal::MAX_LENGTH_DESCRIPTION),
                'status' => $statuses[Random::int(0, count($statuses) - 1)],
                'limit' => Random::int(1, 100),
                'cursor' => $this->randomString(Random::int(2, 30)),
            ];
            $result[] = [$request];
        }

        return $result;
    }

    private function randomString($length, $any = true): string
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
