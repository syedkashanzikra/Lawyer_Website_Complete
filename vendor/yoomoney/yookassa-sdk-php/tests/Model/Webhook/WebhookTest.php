<?php

namespace Tests\YooKassa\Model\Webhook;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Webhook\Webhook;

/**
 * @internal
 */
class WebhookTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $data
     */
    public function testWebhookInstantiate($data): void
    {
        $webhook = new Webhook();

        $webhook->setId($data['id']);
        $webhook->setUrl($data['url']);
        $webhook->setEvent($data['event']);

        self::assertEquals($webhook->getId(), $data['id']);
        self::assertEquals($webhook->getUrl(), $data['url']);
        self::assertEquals($webhook->getEvent(), $data['event']);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $data
     */
    public function testWebhookConstructorInstantiate($data): void
    {
        $webhook = new Webhook($data);

        self::assertEquals($webhook->getId(), $data['id']);
        self::assertEquals($webhook->getUrl(), $data['url']);
        self::assertEquals($webhook->getEvent(), $data['event']);
    }

    public static function validDataProvider(): array
    {
        return [
            [
                [
                    'id' => Random::str(20),
                    'event' => NotificationEventType::REFUND_SUCCEEDED,
                    'url' => 'https://merchant-site.ru/notification_url',
                ],
            ],
            [
                [
                    'id' => Random::str(20),
                    'event' => NotificationEventType::PAYMENT_SUCCEEDED,
                    'url' => 'https://merchant-site.ru/notification_url',
                ],
            ],
            [
                [
                    'id' => Random::str(20),
                    'event' => NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE,
                    'url' => 'https://merchant-site.ru/notification_url',
                ],
            ],
        ];
    }
}
