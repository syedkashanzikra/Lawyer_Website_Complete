<?php

namespace Tests\YooKassa\Model\Notification;

use Exception;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationRefundSucceeded;
use YooKassa\Model\Notification\NotificationType;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Refund\RefundInterface;
use YooKassa\Model\Refund\RefundStatus;

/**
 * @internal
 */
class NotificationRefundSucceededTest extends AbstractTestNotification
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetObject(array $value): void
    {
        $instance = $this->getTestInstance($value);
        self::assertInstanceOf(RefundInterface::class, $instance->getObject());
        self::assertEquals($value['object']['id'], $instance->getObject()->getId());
    }

    /**
     * @throws Exception
     */
    public function validDataProvider(): array
    {
        $result = [];
        $statuses = RefundStatus::getValidValues();
        $receiptRegistrations = ReceiptRegistrationStatus::getValidValues();

        for ($i = 0; $i < 10; $i++) {
            $refund = [
                'id' => Random::str(36),
                'payment_id' => Random::str(36),
                'status' => Random::value($statuses),
                'amount' => [
                    'value' => Random::float(0.01, 1000000.0),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'receipt_registration' => Random::value($receiptRegistrations),
                'description' => Random::str(1, 128),
            ];
            $result[] = [
                [
                    'type' => $this->getExpectedType(),
                    'event' => $this->getExpectedEvent(),
                    'object' => $refund,
                ],
            ];
        }

        return $result;
    }

    protected function getTestInstance(array $source): NotificationRefundSucceeded
    {
        return new NotificationRefundSucceeded($source);
    }

    protected function getExpectedType(): string
    {
        return NotificationType::NOTIFICATION;
    }

    protected function getExpectedEvent(): string
    {
        return NotificationEventType::REFUND_SUCCEEDED;
    }
}
