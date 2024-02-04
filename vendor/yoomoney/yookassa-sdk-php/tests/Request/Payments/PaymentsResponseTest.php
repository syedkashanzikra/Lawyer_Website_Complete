<?php

namespace Tests\YooKassa\Request\Payments;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Payment\CancellationDetailsPartyCode;
use YooKassa\Model\Payment\CancellationDetailsReasonCode;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Model\Payment\PaymentInterface;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Request\Payments\PaymentsResponse;

/**
 * @internal
 */
class PaymentsResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetItems(array $options): void
    {
        $instance = new PaymentsResponse($options);
        self::assertSameSize($options['items'], $instance->getItems());
        foreach ($instance->getItems() as $index => $item) {
            self::assertInstanceOf(PaymentInterface::class, $item);
            self::assertArrayHasKey($index, $options['items']);
            self::assertEquals($options['items'][$index]['id'], $item->getId());
            self::assertEquals($options['items'][$index]['status'], $item->getStatus());
            self::assertEquals($options['items'][$index]['amount']['value'], $item->getAmount()->getValue());
            self::assertEquals($options['items'][$index]['amount']['currency'], $item->getAmount()->getCurrency());
            self::assertEquals($options['items'][$index]['created_at'], $item->getCreatedAt()->format(YOOKASSA_DATE));
            self::assertEquals($options['items'][$index]['payment_method']['type'], $item->getPaymentMethod()->getType());
            self::assertEquals($options['items'][$index]['paid'], $item->getPaid());
            self::assertEquals($options['items'][$index]['refundable'], $item->getRefundable());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetNextCursor(array $options): void
    {
        $instance = new PaymentsResponse($options);
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
        $instance = new PaymentsResponse($options);
        if (empty($options['next_cursor'])) {
            self::assertFalse($instance->hasNextCursor());
        } else {
            self::assertTrue($instance->hasNextCursor());
        }
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidData(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);
        new PaymentsResponse($options);
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
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => false,
                            'refundable' => false,
                            'confirmation' => [
                                'type' => ConfirmationType::EXTERNAL,
                            ],
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                        ],
                    ],
                    'next_cursor' => uniqid('', true),
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => true,
                            'refundable' => true,
                            'confirmation' => [
                                'type' => ConfirmationType::EXTERNAL,
                            ],
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                        ],
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => false,
                            'refundable' => false,
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                            'reference_id' => uniqid('', true),
                            'captured_at' => date(YOOKASSA_DATE),
                            'charge' => ['value' => Random::int(1, 100000), 'currency' => CurrencyCode::RUB],
                            'income' => ['value' => Random::int(1, 100000), 'currency' => CurrencyCode::USD],
                            'refunded' => ['value' => Random::int(1, 100000), 'currency' => CurrencyCode::EUR],
                            'metadata' => ['test_key' => 'test_value'],
                            'cancellation_details' => ['party' => CancellationDetailsPartyCode::PAYMENT_NETWORK, 'reason' => CancellationDetailsReasonCode::INVALID_CSC],
                            'authorization_details' => ['rrn' => Random::str(20), 'auth_code' => Random::str(20), 'three_d_secure' => ['applied' => Random::bool()]],
                            'refunded_amount' => ['value' => Random::int(1, 100000), 'currency' => CurrencyCode::RUB],
                            'confirmation' => [
                                'type' => ConfirmationType::EXTERNAL,
                            ],
                            'receipt_registration' => ReceiptRegistrationStatus::PENDING,
                        ],
                    ],
                    'next_cursor' => uniqid('', true),
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => true,
                            'refundable' => true,
                            'confirmation' => [
                                'type' => ConfirmationType::REDIRECT,
                                'confirmation_url' => 'https://test.com',
                                'return_url' => 'https://test.com',
                                'enforce' => false,
                            ],
                            'income_amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                        ],
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => true,
                            'refundable' => true,
                            'confirmation' => [
                                'type' => ConfirmationType::REDIRECT,
                                'confirmation_url' => 'https://test.com',
                            ],
                            'income_amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                        ],
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => true,
                            'refundable' => true,
                            'confirmation' => [
                                'type' => ConfirmationType::CODE_VERIFICATION,
                                'confirmation_url' => Random::str(10),
                            ],
                            'income_amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                        ],
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => true,
                            'refundable' => true,
                            'confirmation' => [
                                'type' => ConfirmationType::EMBEDDED,
                                'confirmation_token' => Random::str(10),
                                'confirmation_url' => Random::str(10),
                            ],
                            'income_amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'recipient' => [
                                'account_id' => uniqid('', true),
                                'gateway_id' => uniqid('', true),
                            ],
                        ],
                    ],
                    'next_cursor' => uniqid('', true),
                ],
            ],
        ];
    }

    public static function invalidDataProvider()
    {
        return [
            [
                [
                    'items' => [
                        [
                            'id' => Random::str(36),
                            'status' => PaymentStatus::SUCCEEDED,
                            'amount' => [
                                'value' => Random::int(1, 100000),
                                'currency' => CurrencyCode::EUR,
                            ],
                            'description' => Random::str(20),
                            'created_at' => date(YOOKASSA_DATE),
                            'payment_method' => [
                                'type' => PaymentMethodType::QIWI,
                            ],
                            'paid' => true,
                            'refundable' => true,
                            'confirmation' => [
                                'confirmation_url' => Random::str(10),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
