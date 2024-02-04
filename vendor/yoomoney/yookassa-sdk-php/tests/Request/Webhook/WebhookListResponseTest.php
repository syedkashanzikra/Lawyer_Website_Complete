<?php

namespace Tests\YooKassa\Request\Webhook;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Webhook\Webhook;
use YooKassa\Request\Webhook\WebhookListResponse;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;

/**
 * @internal
 */
class WebhookListResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @throws Exception
     */
    public function testGetType(array $options): void
    {
        $instance = new WebhookListResponse($options);

        self::assertEquals($options['type'], $instance->getType());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @throws Exception
     */
    public function testGetItems(array $options): void
    {
        $instance = new WebhookListResponse($options);

        self::assertEquals(count($options['items']), count($instance->getItems()));

        foreach ($instance->getItems() as $index => $item) {
            self::assertInstanceOf(Webhook::class, $item);
            self::assertArrayHasKey($index, $options['items']);
            self::assertEquals($options['items'][$index]['id'], $item->getId());
            self::assertEquals($options['items'][$index]['event'], $item->getEvent());
            self::assertEquals($options['items'][$index]['url'], $item->getUrl());
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @throws Exception
     */
    public function testInvalidData(array $options, string $exception)
    {
        $this->expectException($exception);
        new WebhookListResponse($options);
    }

    public function validDataProvider()
    {
        return [
            [
                [
                    'type' => 'list',
                    'items' => $this->generateWebhooks(),
                ],
            ],
        ];
    }

    private function generateWebhooks()
    {
        $return = [];
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateWebhook();
        }

        return $return;
    }

    private function generateWebhook()
    {
        return [
            'id' => Random::str(39),
            'event' => Random::value(NotificationEventType::getValidValues()),
            'url' => 'https://merchant-site.ru/notification_url',
        ];
    }

    public function invalidDataProvider()
    {
        return [
            [
                [
                    'type' => 'list',
                    'items' => [new Webhook()],
                ],
                EmptyPropertyValueException::class
            ],
            [
                [
                    'type' => 'test',
                    'items' => [],
                ],
                InvalidArgumentException::class
            ],
            [
                [
                    'type' => 'list',
                    'items' => [new Webhook(
                        [
                            'id' => Random::str(20),
                            'event' => NotificationEventType::REFUND_SUCCEEDED,
                            'url' => 'https://merchant-site.ru/notification_url',
                        ],
                    )],
                    'next_cursor' => 123
                ],
                InvalidArgumentException::class
            ]
        ];
    }
}
