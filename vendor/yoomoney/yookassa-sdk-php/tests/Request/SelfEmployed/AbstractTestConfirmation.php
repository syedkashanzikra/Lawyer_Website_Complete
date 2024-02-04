<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation;

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
    public function testInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestConfirmation($value);
    }

    /**
     * @throws Exception
     */
    public static function invalidTypeDataProvider(): array
    {
        return [
            [''],
            [Random::str(40)],
            [0],
        ];
    }

    abstract protected function getTestInstance(): SelfEmployedRequestConfirmation;

    abstract protected function getExpectedType(): string;
}

class TestConfirmation extends SelfEmployedRequestConfirmation
{
    public function __construct($type)
    {
        parent::__construct();
        $this->setType($type);
    }
}
