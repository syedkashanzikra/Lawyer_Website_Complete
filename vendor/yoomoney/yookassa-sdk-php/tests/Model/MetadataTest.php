<?php

namespace Tests\YooKassa\Model;

use PHPUnit\Framework\TestCase;
use YooKassa\Model\Metadata;

/**
 * @internal
 */
class MetadataTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testToArray(array $source): void
    {
        $instance = new Metadata();
        foreach ($source as $key => $value) {
            $instance->offsetSet($key, $value);
        }
        self::assertEquals($source, $instance->toArray());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCount(array $source): void
    {
        $instance = new Metadata();
        $count = 0;
        self::assertEquals($count, $instance->count());
        foreach ($source as $key => $value) {
            $instance->offsetSet($key, $value);
            ++$count;
            self::assertEquals($count, $instance->count());
        }
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetIterator(array $source): void
    {
        $instance = new Metadata();
        foreach ($source as $key => $value) {
            $instance->offsetSet($key, $value);
        }

        $iterator = $instance->getIterator();
        $tmp = $source;
        for ($iterator->rewind(); $iterator->valid(); $iterator->next()) {
            self::assertArrayHasKey($iterator->key(), $source);
            self::assertEquals($source[$iterator->key()], $iterator->current());
            unset($tmp[$iterator->key()]);
        }
        self::assertCount(0, $tmp);

        $tmp = $source;
        foreach ($instance as $key => $value) {
            self::assertArrayHasKey($key, $source);
            self::assertEquals($source[$key], $value);
            unset($tmp[$key]);
        }
        self::assertCount(0, $tmp);
    }

    public static function dataProvider()
    {
        return [
            [
                ['testKey' => 'testValue'],
            ],
            [
                [
                    'testKey1' => 'testValue1',
                    'testKey2' => 'testValue2',
                ],
            ],
            [
                [
                    'testKey1' => 'testValue1',
                    'testKey2' => 'testValue2',
                    'testKey3' => 'testValue3',
                ],
            ],
        ];
    }
}
