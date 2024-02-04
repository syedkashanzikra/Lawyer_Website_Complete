<?php

namespace Tests\YooKassa\Request\Payouts;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\SbpParticipantBank;
use YooKassa\Request\Payouts\SbpBanksResponse;

/**
 * @internal
 */
class SbpBanksResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetItems(array $options): void
    {
        $instance = new SbpBanksResponse($options);

        self::assertEquals($options['type'], $instance->getType());
        self::assertEquals($options['next_cursor'], $instance->getNextCursor());
        if (!empty($options['items'])) {
            self::assertSameSize($options['items'], $instance->getItems());

            foreach ($instance->getItems() as $index => $item) {
                self::assertInstanceOf(SbpParticipantBank::class, $item);
                self::assertArrayHasKey($index, $options['items']);
                self::assertEquals($options['items'][$index]['bank_id'], $item->getBankId());
                self::assertEquals($options['items'][$index]['name'], $item->getName());
                self::assertEquals($options['items'][$index]['bic'], $item->getBic());
            }
        } else {
            self::assertEmpty($instance->getItems());
        }
    }

    public static function validDataProvider(): array
    {
        return [
            [
                [
                    'type' => 'list',
                    'items' => [],
                    'next_cursor' => Random::str(10),
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                            'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                            'bic' => Random::str(9, '0123456789'),
                        ],
                    ],
                    'next_cursor' => Random::str(10, 100),
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                            'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                            'bic' => Random::str(9, '0123456789'),
                        ],
                        [
                            'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                            'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                            'bic' => Random::str(9, '0123456789'),
                        ],
                    ],
                    'next_cursor' => '37a5c87d-3984-51e8-a7f3-8de646d39ec15',
                ],
            ],
            [
                [
                    'type' => 'list',
                    'items' => [
                        [
                            'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                            'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                            'bic' => Random::str(9, '0123456789'),
                        ],
                        [
                            'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                            'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                            'bic' => Random::str(9, '0123456789'),
                        ],
                        [
                            'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                            'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                            'bic' => Random::str(9, '0123456789'),
                        ],
                    ],
                    'next_cursor' => Random::str(Random::int(2, 32)),
                ],
            ],
        ];
    }
}
