<?php

namespace Tests\YooKassa\Request\Payments;

use DateTime;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Request\Payments\PaymentsRequest;
use YooKassa\Request\Payments\PaymentsRequestSerializer;

/**
 * @internal
 */
class PaymentsRequestSerializerTest extends TestCase
{
    private array $fieldMap = [
        'createdAtGte' => 'created_at.gte',
        'createdAtGt' => 'created_at.gt',
        'createdAtLte' => 'created_at.lte',
        'createdAtLt' => 'created_at.lt',
        'capturedAtGte' => 'captured_at.gte',
        'capturedAtGt' => 'captured_at.gt',
        'capturedAtLte' => 'captured_at.lte',
        'capturedAtLt' => 'captured_at.lt',
        'status' => 'status',
        'paymentMethod' => 'payment_method',
        'limit' => 'limit',
        'cursor' => 'cursor',
    ];

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSerialize(mixed $options): void
    {
        $serializer = new PaymentsRequestSerializer();
        $data = $serializer->serialize(PaymentsRequest::builder()->build($options));

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
                [],
            ],
            [
                [
                    'createdAtGte' => '',
                    'createdAtGt' => '',
                    'createdAtLte' => '',
                    'createdAtLt' => '',
                    'capturedAtGte' => '',
                    'capturedAtGt' => '',
                    'capturedAtLte' => '',
                    'capturedAtLt' => '',
                    'paymentMethod' => '',
                    'status' => '',
                    'limit' => 1,
                    'cursor' => '',
                ],
            ],
        ];
        $statuses = PaymentStatus::getValidValues();
        $methods = PaymentMethodType::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'createdAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'capturedAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'capturedAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'capturedAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'capturedAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'paymentMethod' => $methods[Random::int(0, count($methods) - 1)],
                'status' => $statuses[Random::int(0, count($statuses) - 1)],
                'limit' => Random::int(1, 100),
                'cursor' => Random::str(Random::int(2, 30)),
            ];
            $result[] = [$request];
        }

        return $result;
    }

}
