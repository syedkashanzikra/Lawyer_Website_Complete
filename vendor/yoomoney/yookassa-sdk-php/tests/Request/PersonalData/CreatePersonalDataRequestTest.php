<?php

namespace Tests\YooKassa\Request\PersonalData;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Metadata;
use YooKassa\Model\PersonalData\PersonalDataType;
use YooKassa\Request\PersonalData\CreatePersonalDataRequest;
use YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder;

/**
 * @internal
 */
class CreatePersonalDataRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testLastName($options): void
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasLastName());
        self::assertNull($instance->getLastName());
        self::assertNull($instance->last_name);

        $expected = $options['last_name'];

        $instance->setLastName($options['last_name']);
        if (empty($options['last_name'])) {
            self::assertFalse($instance->hasLastName());
            self::assertNull($instance->getLastName());
            self::assertNull($instance->last_name);
        } else {
            self::assertTrue($instance->hasLastName());
            self::assertSame($expected, $instance->getLastName());
            self::assertSame($expected, $instance->last_name);
        }

        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasLastName());
        self::assertNull($instance->getLastName());
        self::assertNull($instance->last_name);

        $instance->last_name = $options['last_name'];
        if (empty($options['last_name'])) {
            self::assertFalse($instance->hasLastName());
            self::assertNull($instance->getLastName());
            self::assertNull($instance->last_name);
        } else {
            self::assertTrue($instance->hasLastName());
            self::assertSame($expected, $instance->getLastName());
            self::assertSame($expected, $instance->last_name);
        }
    }

    /**
     * @dataProvider invalidLastNameDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLastName($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePersonalDataRequest();
        $instance->setLastName($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testFirstName($options): void
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasFirstName());
        self::assertNull($instance->getFirstName());
        self::assertNull($instance->first_name);

        $expected = $options['first_name'];

        $instance->setFirstName($options['first_name']);
        if (empty($options['first_name'])) {
            self::assertFalse($instance->hasFirstName());
            self::assertNull($instance->getFirstName());
            self::assertNull($instance->first_name);
        } else {
            self::assertTrue($instance->hasFirstName());
            self::assertSame($expected, $instance->getFirstName());
            self::assertSame($expected, $instance->first_name);
        }

        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasFirstName());
        self::assertNull($instance->getFirstName());
        self::assertNull($instance->first_name);

        $instance->first_name = $options['first_name'];
        if (empty($options['first_name'])) {
            self::assertFalse($instance->hasFirstName());
            self::assertNull($instance->getFirstName());
            self::assertNull($instance->first_name);
        } else {
            self::assertTrue($instance->hasFirstName());
            self::assertSame($expected, $instance->getFirstName());
            self::assertSame($expected, $instance->first_name);
        }
    }

    /**
     * @dataProvider invalidFirstNameDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidFirstName($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePersonalDataRequest();
        $instance->setFirstName($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testMiddleName($options): void
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasMiddleName());
        self::assertNull($instance->getMiddleName());
        self::assertNull($instance->middle_name);

        $expected = $options['middle_name'];

        $instance->setMiddleName($options['middle_name']);
        if (empty($options['middle_name'])) {
            self::assertFalse($instance->hasMiddleName());
            self::assertNull($instance->getMiddleName());
            self::assertNull($instance->middle_name);
        } else {
            self::assertTrue($instance->hasMiddleName());
            self::assertSame($expected, $instance->getMiddleName());
            self::assertSame($expected, $instance->middle_name);
        }

        $instance->setMiddleName(null);
        self::assertFalse($instance->hasMiddleName());
        self::assertNull($instance->getMiddleName());
        self::assertNull($instance->middle_name);

        $instance->middle_name = $options['middle_name'];
        if (empty($options['middle_name'])) {
            self::assertFalse($instance->hasMiddleName());
            self::assertNull($instance->getMiddleName());
            self::assertNull($instance->middle_name);
        } else {
            self::assertTrue($instance->hasMiddleName());
            self::assertSame($expected, $instance->getMiddleName());
            self::assertSame($expected, $instance->middle_name);
        }
    }

    /**
     * @dataProvider invalidMiddleNameDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMiddleName($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePersonalDataRequest();
        $instance->setMiddleName($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testMetadata($options): void
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $expected = $options['metadata'];
        if ($expected instanceof Metadata) {
            $expected = $expected->toArray();
        }

        $instance->setMetadata($options['metadata']);
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }

        $instance->setMetadata(null);
        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $instance->metadata = $options['metadata'];
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMetadata($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePersonalDataRequest();
        $instance->setMetadata($value);
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePersonalDataRequest();
        $instance->setType($value);
    }

    public function testValidate(): void
    {
        $instance = new CreatePersonalDataRequest();

        self::assertFalse($instance->validate());

        $instance->setType(Random::value(PersonalDataType::getEnabledValues()));
        self::assertFalse($instance->validate());

        $instance->setMiddleName('test');
        self::assertFalse($instance->validate());

        $instance->setLastName('test');
        self::assertFalse($instance->validate());
        $instance->setFirstName('test');
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = CreatePersonalDataRequest::builder();
        self::assertInstanceOf(CreatePersonalDataRequestBuilder::class, $builder);
    }

    public static function validDataProvider()
    {
        $metadata = new Metadata();
        $metadata->test = 'test';
        $result = [
            [
                [
                    'type' => Random::value(PersonalDataType::getEnabledValues()),
                    'last_name' => 'null',
                    'first_name' => 'null',
                    'middle_name' => null,
                    'metadata' => null,
                ],
            ],
            [
                [
                    'type' => Random::value(PersonalDataType::getEnabledValues()),
                    'last_name' => 'null',
                    'first_name' => 'null',
                    'middle_name' => '',
                    'metadata' => [],
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'type' => Random::value(PersonalDataType::getEnabledValues()),
                'last_name' => Random::str(5, CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME, 'abcdefghijklmnopqrstuvwxyz'),
                'first_name' => Random::str(5, CreatePersonalDataRequest::MAX_LENGTH_FIRST_NAME, 'abcdefghijklmnopqrstuvwxyz'),
                'middle_name' => Random::str(5, CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME, 'abcdefghijklmnopqrstuvwxyz'),
                'metadata' => ($i % 2) ? $metadata : ['test' => 'test'],
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidTypeDataProvider()
    {
        return [
            [false],
            [true],
            [Random::str(10)],
        ];
    }

    public static function invalidMetadataDataProvider()
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
        ];
    }

    public static function invalidLastNameDataProvider()
    {
        return [
            [null],
            [''],
            [false],
            [Random::str(CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME + 1)],
        ];
    }

    public static function invalidMiddleNameDataProvider()
    {
        return [
            [Random::str(CreatePersonalDataRequest::MAX_LENGTH_LAST_NAME + 1)],
        ];
    }

    public static function invalidFirstNameDataProvider()
    {
        return [
            [null],
            [''],
            [false],
            [Random::str(CreatePersonalDataRequest::MAX_LENGTH_FIRST_NAME + 1)],
        ];
    }
}
