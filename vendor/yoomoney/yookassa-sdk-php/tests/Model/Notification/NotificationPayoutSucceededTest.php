<?php

namespace Tests\YooKassa\Model\Notification;

use Exception;
use YooKassa\Helpers\Random;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationPayoutSucceeded;
use YooKassa\Model\Notification\NotificationType;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\Payout;
use YooKassa\Model\Payout\PayoutInterface;
use YooKassa\Model\Payout\PayoutStatus;

/**
 * @internal
 */
class NotificationPayoutSucceededTest extends AbstractTestNotification
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetObject(array $value): void
    {
        $instance = $this->getTestInstance($value);
        self::assertInstanceOf(PayoutInterface::class, $instance->getObject());
        self::assertEquals($value['object']['id'], $instance->getObject()->getId());
    }

    /**
     * @throws Exception
     */
    public function validDataProvider(): array
    {
        $result = [];
        $payoutDestinations = [
            PaymentMethodType::YOO_MONEY => [
                'type' => PaymentMethodType::YOO_MONEY,
                'account_number' => Random::str(11, 33, '1234567890'),
            ],
            PaymentMethodType::BANK_CARD => [
                'type' => PaymentMethodType::BANK_CARD,
                'card' => [
                    'first6' => Random::str(6, 6, '1234567890'),
                    'last4' => Random::str(4, 4, '1234567890'),
                    'card_type' => Random::value(BankCardType::getValidValues())
                ],
            ],
        ];

        $result[] = [
            [
                'type' => $this->getExpectedType(),
                'event' => $this->getExpectedEvent(),
                'object' => [
                    'id' => Random::str(36, 50),
                    'status' => Random::value(PayoutStatus::getValidValues()),
                    'amount' => ['value' => Random::int(1, 10000), 'currency' => 'RUB'],
                    'description' => Random::str(1, Payout::MAX_LENGTH_DESCRIPTION),
                    'payout_destination' => $payoutDestinations[Random::value([PaymentMethodType::YOO_MONEY, PaymentMethodType::BANK_CARD])],
                    'created_at' => date(YOOKASSA_DATE, Random::int(111111111, time())),
                    'test' => true,
                    'deal' => ['id' => Random::str(36, 50)],
                    'metadata' => ['order_id' => '37'],
                ],
            ],
        ];

        for ($i = 0; $i < 20; $i++) {
            $object = [
                'id' => Random::str(36, 50),
                'status' => Random::value(PayoutStatus::getValidValues()),
                'amount' => ['value' => Random::int(1, 10000), 'currency' => 'RUB'],
                'description' => (0 === $i ? null : (1 === $i ? '' : (2 === $i ? Random::str(Payout::MAX_LENGTH_DESCRIPTION)
                    : Random::str(1, Payout::MAX_LENGTH_DESCRIPTION)))),
                'payout_destination' => $payoutDestinations[Random::value([PaymentMethodType::YOO_MONEY, PaymentMethodType::BANK_CARD])],
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'test' => (bool) ($i % 2),
                'metadata' => [Random::str(3, 128, 'abcdefghijklmnopqrstuvwxyz') => Random::str(1, 512)],
            ];
            $result[] = [
                [
                    'type' => $this->getExpectedType(),
                    'event' => $this->getExpectedEvent(),
                    'object' => $object,
                ],
            ];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    protected function getTestInstance(array $source): NotificationPayoutSucceeded
    {
        return new NotificationPayoutSucceeded($source);
    }

    protected function getExpectedType(): string
    {
        return NotificationType::NOTIFICATION;
    }

    protected function getExpectedEvent(): string
    {
        return NotificationEventType::PAYOUT_SUCCEEDED;
    }
}
