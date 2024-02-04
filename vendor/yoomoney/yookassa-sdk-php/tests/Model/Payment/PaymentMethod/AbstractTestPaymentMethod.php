<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\AbstractPaymentMethod;

abstract class AbstractTestPaymentMethod extends TestCase
{
    public function testGetType(): void
    {
        $instance = $this->getTestInstance();
        self::assertEquals($this->getExpectedType(), $instance->getType());
    }

    /**
     * @dataProvider invalidTypeDataProvider
     */
    public function testInvalidType(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestPaymentData($value);
    }

    /**
     * @dataProvider validSavedDataProvider
     */
    public function testGetSetSaved(mixed $value): void
    {
        $instance = $this->getTestInstance();

        self::assertFalse($instance->getSaved());
        self::assertFalse($instance->saved);

        $instance->setSaved($value);
        if ($value) {
            self::assertTrue($instance->getSaved());
            self::assertTrue($instance->saved);
        } else {
            self::assertFalse($instance->getSaved());
            self::assertFalse($instance->saved);
        }

        $instance = $this->getTestInstance();
        $instance->saved = $value;
        if ($value) {
            self::assertTrue($instance->getSaved());
            self::assertTrue($instance->saved);
        } else {
            self::assertFalse($instance->getSaved());
            self::assertFalse($instance->saved);
        }
    }

    /**
     * @dataProvider validIdDataProvider
     */
    public function testGetSetId(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setId($value);
        if (empty($value)) {
            self::assertNull($instance->getId());
            self::assertNull($instance->id);
        } else {
            self::assertEquals($value, $instance->getId());
            self::assertEquals($value, $instance->id);
        }

        $instance = $this->getTestInstance();
        $instance->id = $value;
        if (empty($value)) {
            self::assertNull($instance->getId());
            self::assertNull($instance->id);
        } else {
            self::assertEquals($value, $instance->getId());
            self::assertEquals($value, $instance->id);
        }
    }

    /**
     * @dataProvider validTitleDataProvider
     */
    public function testGetSetTitle(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setTitle($value);
        if (empty($value)) {
            self::assertNull($instance->getTitle());
            self::assertNull($instance->title);
        } else {
            self::assertEquals($value, $instance->getTitle());
            self::assertEquals($value, $instance->title);
        }

        $instance = $this->getTestInstance();
        $instance->title = $value;
        if (empty($value)) {
            self::assertNull($instance->getTitle());
            self::assertNull($instance->title);
        } else {
            self::assertEquals($value, $instance->getTitle());
            self::assertEquals($value, $instance->title);
        }
    }

    public static function invalidTypeDataProvider()
    {
        return [
            [''],
            [null],
            [Random::str(40)],
            [0],
        ];
    }

    public static function validSavedDataProvider()
    {
        return [
            [true],
            [false],
        ];
    }

    public static function validIdDataProvider()
    {
        return [
            [null],
            [Random::str(2)],
            [Random::str(10)],
            [Random::str(100)],
        ];
    }

    public static function validTitleDataProvider()
    {
        return [
            [null],
            [Random::str(2, 2, '123456789ABCDEF')],
            [Random::str(2)],
            [Random::str(10)],
            [Random::str(100)],
        ];
    }

    abstract protected function getTestInstance(): AbstractPaymentMethod;

    abstract protected function getExpectedType(): string;
}

class TestPaymentData extends AbstractPaymentMethod
{
    public function __construct($type)
    {
        parent::__construct([]);
        $this->setType($type);
    }
}
