<?php

namespace Tests\YooKassa\Request\Deals;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\DealInterface;
use YooKassa\Model\Deal\DealStatus;
use YooKassa\Model\Deal\DealType;
use YooKassa\Model\Deal\FeeMoment;
use YooKassa\Request\Deals\DealsResponse;

/**
 * @internal
 */
class DealsResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetItems(array $options): void
    {
        $instance = new DealsResponse($options);
        if (!empty($options['items'])) {
            self::assertEquals(count($options['items']), count($instance->getItems()));
            foreach ($instance->getItems() as $index => $item) {
                self::assertInstanceOf(DealInterface::class, $item);
                self::assertArrayHasKey($index, $options['items']);
                self::assertEquals($options['items'][$index]['id'], $item->getId());
                self::assertEquals($options['items'][$index]['type'], $item->getType());
                self::assertEquals($options['items'][$index]['status'], $item->getStatus());
                self::assertEquals($options['items'][$index]['fee_moment'], $item->getFeeMoment());
                self::assertEquals($options['items'][$index]['balance']['value'], $item->getBalance()->getValue());
                self::assertEquals($options['items'][$index]['balance']['currency'], $item->getBalance()->getCurrency());
                self::assertEquals($options['items'][$index]['payout_balance']['value'], $item->getPayoutBalance()->getValue());
                self::assertEquals($options['items'][$index]['payout_balance']['currency'], $item->getPayoutBalance()->getCurrency());
                self::assertEquals($options['items'][$index]['created_at'], $item->getCreatedAt()->format(YOOKASSA_DATE));
                self::assertEquals($options['items'][$index]['expires_at'], $item->getExpiresAt()->format(YOOKASSA_DATE));
                self::assertEquals($options['items'][$index]['test'], $item->getTest());
                self::assertEquals($options['items'][$index]['description'], $item->getDescription());
            }
        } else {
            self::assertEmpty($instance->getItems());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetNextCursor(array $options): void
    {
        $instance = new DealsResponse($options);
        if (empty($options['next_cursor'])) {
            self::assertNull($instance->getNextCursor());
        } else {
            self::assertEquals($options['next_cursor'], $instance->getNextCursor());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testHasNext(array $options): void
    {
        $instance = new DealsResponse($options);
        if (empty($options['next_cursor'])) {
            self::assertFalse($instance->hasNextCursor());
        } else {
            self::assertTrue($instance->hasNextCursor());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetType(array $options): void
    {
        $instance = new DealsResponse($options);
        if (empty($options['type'])) {
            self::assertEquals('list', $instance->getType());
        } else {
            self::assertEquals($options['type'], $instance->getType());
        }
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidData(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DealsResponse($options);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $statuses = DealStatus::getValidValues();
        $types = DealType::getValidValues();

        return [
            [
                [
                    'items' => [],
                ],
            ],
            [
                [
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'type' => Random::value($types),
                            'status' => Random::value($statuses),
                            'description' => Random::str(128),
                            'balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'payout_balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                            'test' => Random::bool(),
                            'metadata' => [],
                        ],
                    ],
                    'next_cursor' => uniqid('', true),
                    'type' => 'list'
                ],
            ],
            [
                [
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'type' => Random::value($types),
                            'status' => Random::value($statuses),
                            'description' => Random::str(128),
                            'balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'payout_balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                            'test' => Random::bool(),
                            'metadata' => [],
                        ],
                        [
                            'id' => Random::str(36),
                            'type' => Random::value($types),
                            'status' => Random::value($statuses),
                            'description' => Random::str(128),
                            'balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'payout_balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                            'test' => Random::bool(),
                            'metadata' => [],
                        ],
                    ],
                    'next_cursor' => uniqid('', true),
                ],
            ],
            [
                [
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'type' => Random::value($types),
                            'status' => Random::value($statuses),
                            'description' => Random::str(128),
                            'balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'payout_balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                            'test' => Random::bool(),
                            'metadata' => [],
                        ],
                        [
                            'id' => Random::str(36),
                            'type' => Random::value($types),
                            'status' => Random::value($statuses),
                            'description' => Random::str(128),
                            'balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'payout_balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                            'test' => Random::bool(),
                            'metadata' => [],
                        ],
                        [
                            'id' => Random::str(36),
                            'type' => Random::value($types),
                            'status' => Random::value($statuses),
                            'description' => Random::str(128),
                            'balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'payout_balance' => [
                                'value' => number_format(Random::float(0.01, 1000000.0), 2, '.', ''),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                            'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                            'test' => Random::bool(),
                            'metadata' => [],
                        ],
                    ],
                    'next_cursor' => uniqid('', true),
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidDataProvider(): array
    {
        return [
            [
                [
                    'next_cursor' => uniqid('', true),
                    'items' => [
                        [
                            'id' => 'null',
                            'type' => 'null',
                            'status' => null,
                            'description' => [],
                            'balance' => null,
                            'payout_balance' => null,
                            'created_at' => [],
                            'expires_at' => [],
                            'fee_moment' => Random::bool(),
                            'test' => null,
                            'metadata' => 'test',
                        ],
                    ],
                ],
            ],
        ];
    }
}
