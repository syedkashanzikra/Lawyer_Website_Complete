<?php

namespace Tests\YooKassa\Request\Deals;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\DealType;
use YooKassa\Model\Deal\FeeMoment;
use YooKassa\Request\Deals\CreateDealRequest;
use YooKassa\Request\Deals\CreateDealRequestSerializer;

/**
 * @internal
 */
class CreateDealRequestSerializerTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSerialize($options): void
    {
        $serializer = new CreateDealRequestSerializer();
        $instance = CreateDealRequest::builder()->build($options);
        $data = $serializer->serialize($instance);

        $expected = [
            'type' => $options['type'],
            'fee_moment' => $options['fee_moment'],
        ];

        if (!empty($options['metadata'])) {
            $expected['metadata'] = [];
            foreach ($options['metadata'] as $key => $value) {
                $expected['metadata'][$key] = $value;
            }
        }

        if (!empty($options['description'])) {
            $expected['description'] = $options['description'];
        }

        self::assertEquals($expected, $data);
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $result = [
            [
                [
                    'type' => Random::value(DealType::getValidValues()),
                    'fee_moment' => Random::value(FeeMoment::getValidValues()),
                    'description' => null,
                    'metadata' => null,
                ],
            ],
            [
                [
                    'type' => Random::value(DealType::getValidValues()),
                    'fee_moment' => Random::value(FeeMoment::getValidValues()),
                    'description' => '',
                    'metadata' => [],
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'type' => Random::value(DealType::getValidValues()),
                'fee_moment' => Random::value(FeeMoment::getValidValues()),
                'description' => Random::str(2, 128),
                'metadata' => [Random::str(1, 30) => Random::str(1, 128)],
            ];
            $result[] = [$request];
        }

        return $result;
    }
}
