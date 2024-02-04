<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\Confirmation\AbstractConfirmation;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

abstract class AbstractTestConfirmation extends TestCase
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
    public function testInvalidType($value, $exception): void
    {
        $this->expectException($exception);
        new TestConfirmation($value);
    }

    /**
     * @throws Exception
     */
    public static function invalidTypeDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(40), InvalidPropertyValueException::class],
            ['test', InvalidPropertyValueException::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    abstract protected function getTestInstance(): AbstractConfirmation;

    abstract protected function getExpectedType(): string;
}

class TestConfirmation extends AbstractConfirmation
{
    public function __construct($type)
    {
        parent::__construct([]);
        $this->setType($type);
    }
}
