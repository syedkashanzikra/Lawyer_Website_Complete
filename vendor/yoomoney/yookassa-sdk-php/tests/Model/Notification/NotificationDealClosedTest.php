<?php

namespace Tests\YooKassa\Model\Notification;

use Exception;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\DealInterface;
use YooKassa\Model\Deal\DealStatus;
use YooKassa\Model\Deal\DealType;
use YooKassa\Model\Deal\FeeMoment;
use YooKassa\Model\Notification\NotificationDealClosed;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationType;

/**
 * @internal
 */
class NotificationDealClosedTest extends AbstractTestNotification
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetObject(array $value): void
    {
        $instance = $this->getTestInstance($value);
        self::assertInstanceOf(DealInterface::class, $instance->getObject());
        self::assertEquals($value['object']['id'], $instance->getObject()->getId());
    }

    /**
     * @throws Exception
     */
    public function validDataProvider(): array
    {
        $result = [];
        $statuses = DealStatus::getValidValues();
        $types = DealType::getValidValues();

        for ($i = 0; $i < 10; $i++) {
            $deal = [
                'id' => Random::str(36),
                'type' => Random::value($types),
                'status' => Random::value($statuses),
                'description' => Random::str(128),
                'balance' => [
                    'value' => Random::float(0.01, 1000000.0),
                    'currency' => Random::value(CurrencyCode::getEnabledValues()),
                ],
                'payout_balance' => [
                    'value' => Random::float(0.01, 1000000.0),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                'test' => (bool) ($i % 2),
                'metadata' => [
                    'value' => Random::float(0.01, 1000000.0),
                    'currency' => Random::str(1, 256),
                ],
            ];
            $result[] = [
                [
                    'type' => $this->getExpectedType(),
                    'event' => $this->getExpectedEvent(),
                    'object' => $deal,
                ],
            ];
        }

        $trueFalse = Random::bool();
        $result[] = [
            [
                'type' => $this->getExpectedType(),
                'event' => $this->getExpectedEvent(),
                'object' => [
                    'id' => Random::str(36),
                    'type' => Random::value($types),
                    'status' => Random::value($statuses),
                    'description' => Random::str(128),
                    'balance' => [
                        'value' => Random::float(0.01, 1000000.0),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                    'payout_balance' => [
                        'value' => Random::float(0.01, 1000000.0),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                    'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'fee_moment' => Random::value(FeeMoment::getEnabledValues()),
                    'test' => $trueFalse,
                    'metadata' => [],
                ],
            ],
        ];

        return $result;
    }

    /**
     * @throws Exception
     */
    protected function getTestInstance(array $source): NotificationDealClosed
    {
        return new NotificationDealClosed($source);
    }

    protected function getExpectedType(): string
    {
        return NotificationType::NOTIFICATION;
    }

    protected function getExpectedEvent(): string
    {
        return NotificationEventType::DEAL_CLOSED;
    }
}
