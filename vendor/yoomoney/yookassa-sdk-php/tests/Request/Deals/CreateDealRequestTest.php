<?php

namespace Tests\YooKassa\Request\Deals;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\DealType;
use YooKassa\Model\Deal\FeeMoment;
use YooKassa\Model\Metadata;
use YooKassa\Request\Deals\CreateDealRequest;
use YooKassa\Request\Deals\CreateDealRequestBuilder;

/**
 * @internal
 */
class CreateDealRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testMetadata($options): void
    {
        $instance = new CreateDealRequest();

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
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testType($options): void
    {
        $instance = new CreateDealRequest();

        self::assertTrue($instance->hasType());
        self::assertNotNull($instance->getType());
        self::assertNotNull($instance->type);

        $instance->setType($options['type']);

        if (empty($options['type'])) {
            self::assertFalse($instance->hasType());
            self::assertNull($instance->getType());
            self::assertNull($instance->type);
        } else {
            self::assertTrue($instance->hasType());
            self::assertSame($options['type'], $instance->getType());
            self::assertSame($options['type'], $instance->type);
        }

        $instance->type = $options['type'];
        if (empty($options['type'])) {
            self::assertFalse($instance->hasType());
            self::assertNull($instance->getType());
            self::assertNull($instance->type);
        } else {
            self::assertTrue($instance->hasType());
            self::assertSame($options['type'], $instance->getType());
            self::assertSame($options['type'], $instance->type);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testFeeMoment($options): void
    {
        $instance = new CreateDealRequest();

        self::assertTrue($instance->hasFeeMoment());
        self::assertNotNull($instance->getFeeMoment());
        self::assertNotNull($instance->fee_moment);
        self::assertNotNull($instance->feeMoment);

        $instance->setFeeMoment($options['fee_moment']);

        if (empty($options['fee_moment'])) {
            self::assertFalse($instance->hasFeeMoment());
            self::assertNull($instance->getFeeMoment());
            self::assertNull($instance->fee_moment);
            self::assertNull($instance->feeMoment);
        } else {
            self::assertTrue($instance->hasFeeMoment());
            self::assertSame($options['fee_moment'], $instance->getFeeMoment());
            self::assertSame($options['fee_moment'], $instance->fee_moment);
            self::assertSame($options['fee_moment'], $instance->feeMoment);
        }

        $instance->fee_moment = $options['fee_moment'];
        if (empty($options['fee_moment'])) {
            self::assertFalse($instance->hasFeeMoment());
            self::assertNull($instance->getFeeMoment());
            self::assertNull($instance->fee_moment);
            self::assertNull($instance->feeMoment);
        } else {
            self::assertTrue($instance->hasFeeMoment());
            self::assertSame($options['fee_moment'], $instance->getFeeMoment());
            self::assertSame($options['fee_moment'], $instance->fee_moment);
            self::assertSame($options['fee_moment'], $instance->feeMoment);
        }
    }

    public function testValidate(): void
    {
        $instance = new CreateDealRequest();

        self::assertTrue($instance->validate());

        $instance->setType(Random::value(DealType::getValidValues()));
        self::assertTrue($instance->validate());

        $instance->setFeeMoment(Random::value(FeeMoment::getValidValues()));
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = CreateDealRequest::builder();
        self::assertInstanceOf(CreateDealRequestBuilder::class, $builder);
    }

    /**
     * @dataProvider invalidFeeMomentDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidFeeMoment($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateDealRequest();
        $instance->setFeeMoment($value);
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMetadata($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateDealRequest();
        $instance->setMetadata($value);
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateDealRequest();
        $instance->setType($value);
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $metadata = new Metadata();
        $metadata->test = 'test';
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'type' => Random::value(DealType::getValidValues()),
                'fee_moment' => Random::value(FeeMoment::getValidValues()),
                'description' => Random::str(1, 128),
                'metadata' => 0 === $i ? $metadata : ['test' => 'test'],
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidFeeMomentDataProvider(): array
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
        ];
    }

    public static function invalidMetadataDataProvider(): array
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
        ];
    }
}
