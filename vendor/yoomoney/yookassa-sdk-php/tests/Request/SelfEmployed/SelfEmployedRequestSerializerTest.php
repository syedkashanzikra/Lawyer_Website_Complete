<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Request\SelfEmployed\SelfEmployedRequest;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestSerializer;

/**
 * @internal
 */
class SelfEmployedRequestSerializerTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSerialize($options): void
    {
        $serializer = new SelfEmployedRequestSerializer();
        $instance = SelfEmployedRequest::builder()->build($options);
        $data = $serializer->serialize($instance);

        $expected = [
            'itn' => $options['itn'],
            'phone' => $options['phone'],
        ];

        if (!empty($options['confirmation'])) {
            $expected['confirmation'] = $options['confirmation'];
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
                    'itn' => Random::str(12, '0123456789'),
                    'phone' => Random::str(4, 15, '0123456789'),
                    'confirmation' => ['type' => Random::value(SelfEmployedConfirmationType::getEnabledValues())],
                ],
            ],
            [
                [
                    'itn' => Random::str(12, '0123456789'),
                    'phone' => Random::str(4, 15, '0123456789'),
                    'confirmation' => ['type' => Random::value(SelfEmployedConfirmationType::getEnabledValues())],
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'itn' => Random::str(12, '0123456789'),
                'phone' => Random::str(4, 15, '0123456789'),
                'confirmation' => ['type' => Random::value(SelfEmployedConfirmationType::getEnabledValues())],
            ];
            $result[] = [$request];
        }

        return $result;
    }
}
