<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\ConfirmationAttributes\AbstractConfirmationAttributes;

abstract class AbstractTestConfirmationAttributes extends TestCase
{
    public function testGetType(): void
    {
        $instance = $this->getTestInstance();
        self::assertEquals($this->getExpectedType(), $instance->getType());
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestConfirmation($value);
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

    /**
     * @dataProvider validLocaleDataProvider
     *
     * @param mixed $value
     */
    public function testSetterLocale($value): void
    {
        $instance = $this->getTestInstance();
        $instance->setLocale($value);
        self::assertEquals((string) $value, $instance->getLocale());
    }

    /**
     * @throws Exception
     */
    public static function validLocaleDataProvider(): array
    {
        return [
            [''],
            [null],
            ['ru_RU'],
            ['en_US'],
        ];
    }

    /**
     * @dataProvider invalidLocaleDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLocale($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setLocale($value);
    }

    /**
     * @throws Exception
     */
    public static function invalidLocaleDataProvider(): array
    {
        return [
            [Random::str(4)],
            [Random::str(6)],
            [0],
        ];
    }

    abstract protected function getTestInstance(): AbstractConfirmationAttributes;

    abstract protected function getExpectedType(): string;
}

class TestConfirmation extends AbstractConfirmationAttributes
{
    public function __construct($type)
    {
        parent::__construct();
        $this->setType($type);
    }
}
