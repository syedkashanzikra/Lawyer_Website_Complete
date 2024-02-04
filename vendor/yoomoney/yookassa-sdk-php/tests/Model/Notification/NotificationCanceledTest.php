<?php

namespace Tests\YooKassa\Model\Notification;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Notification\NotificationCanceled;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationType;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Model\Payment\PaymentInterface;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Receipt\SettlementType;

/**
 * @internal
 */
class NotificationCanceledTest extends AbstractTestNotification
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetObject(array $value): void
    {
        $instance = $this->getTestInstance($value);
        self::assertTrue($instance->getObject() instanceof PaymentInterface);
        self::assertEquals($value['object']['id'], $instance->getObject()->getId());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidFromArray(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance($options);
    }

    /**
     * @throws Exception
     */
    public function validDataProvider(): array
    {
        $result = [];
        $statuses = PaymentStatus::getValidValues();
        $receiptRegistrations = ReceiptRegistrationStatus::getValidValues();

        $confirmations = [
            [
                'type' => ConfirmationType::REDIRECT,
                'confirmation_url' => 'https://confirmation.url',
                'return_url' => 'https://merchant-site.ru/return_url',
                'enforce' => false,
            ],
            [
                'type' => ConfirmationType::EXTERNAL,
            ],
        ];

        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str(36),
                'status' => Random::value($statuses),
                'recipient' => [
                    'account_id' => Random::str(1, 64, '0123456789'),
                    'gateway_id' => Random::str(1, 256),
                ],
                'amount' => [
                    'value' => Random::float(0.01, 1000000.0),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'payment_method' => [
                    'type' => PaymentMethodType::QIWI,
                ],
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'captured_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'confirmation' => Random::value($confirmations),
                'refunded' => [
                    'value' => Random::float(0.01, 1000000.0),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'paid' => $i % 2 ? true : false,
                'refundable' => $i % 2 ? true : false,
                'receipt_registration' => Random::value($receiptRegistrations),
                'metadata' => [
                    'value' => Random::str(1, 256),
                    'currency' => Random::str(1, 256),
                ],
            ];
            $result[] = [
                [
                    'type' => $this->getExpectedType(),
                    'event' => $this->getExpectedEvent(),
                    'object' => $payment,
                ],
            ];
        }

        return $result;
    }

    public static function invalidDataProvider()
    {
        return [
            [
                [
                    'type' => SettlementType::PREPAYMENT,
                ],
            ],
            [
                [
                    'event' => SettlementType::PREPAYMENT,
                ],
            ],
            [
                [
                    'object' => [],
                ],
            ],
        ];
    }

    protected function getTestInstance(array $source): NotificationCanceled
    {
        return new NotificationCanceled($source);
    }

    protected function getExpectedType(): string
    {
        return NotificationType::NOTIFICATION;
    }

    protected function getExpectedEvent(): string
    {
        return NotificationEventType::PAYMENT_CANCELED;
    }
}
