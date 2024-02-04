<?php

namespace Tests\YooKassa\Request\Refunds;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Refund\RefundInterface;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Request\Refunds\RefundsResponse;

/**
 * @internal
 */
class RefundsResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetItems(array $options): void
    {
        $instance = new RefundsResponse($options);
        self::assertSameSize($options['items'], $instance->getItems());
        foreach ($instance->getItems() as $index => $item) {
            self::assertInstanceOf(RefundInterface::class, $item);
            self::assertArrayHasKey($index, $options['items']);
            self::assertEquals($options['items'][$index]['id'], $item->getId());
            self::assertEquals($options['items'][$index]['payment_id'], $item->getPaymentId());
            self::assertEquals($options['items'][$index]['status'], $item->getStatus());
            self::assertEquals($options['items'][$index]['amount']['value'], $item->getAmount()->getValue());
            self::assertEquals($options['items'][$index]['amount']['currency'], $item->getAmount()->getCurrency());
            self::assertEquals($options['items'][$index]['created_at'], $item->getCreatedAt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetNextCursor(array $options): void
    {
        $instance = new RefundsResponse($options);
        if (empty($options['next_cursor'])) {
            self::assertNull($instance->getNextCursor());
        } else {
            self::assertEquals($options['next_cursor'], $instance->getNextCursor());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testHasNextCursor(array $options): void
    {
        $instance = new RefundsResponse($options);
        if (empty($options['next_cursor'])) {
            self::assertFalse($instance->hasNextCursor());
        } else {
            self::assertTrue($instance->hasNextCursor());
        }
    }

    public static function validDataProvider()
    {
        return [
            [
                [
                    'type' => 'list',
                    'items' => [],
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'payment_id' => Random::str(36),
                            'status' => RefundStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(0, time())),
                        ],
                    ],
                    'next_cursor' => Random::str(1, 64),
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'payment_id' => Random::str(36),
                            'status' => RefundStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE),
                        ],
                        [
                            'id' => Random::str(36),
                            'payment_id' => Random::str(36),
                            'status' => RefundStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(0, time())),
                            'authorized_at' => date(YOOKASSA_DATE, Random::int(0, time())),
                            'receipt_registered' => Random::value(ReceiptRegistrationStatus::getValidValues()),
                            'description' => Random::str(64, 250),
                        ],
                    ],
                    'next_cursor' => Random::str(1, 64),
                ],
            ],
        ];
    }
}
