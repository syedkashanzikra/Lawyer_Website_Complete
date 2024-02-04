<?php

namespace Tests\YooKassa\Model\SelfEmployed;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmation;

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
        ];
    }

    abstract protected function getTestInstance(): SelfEmployedConfirmation;

    abstract protected function getExpectedType(): string;
}

class TestConfirmation extends SelfEmployedConfirmation
{
    public function __construct($type)
    {
        parent::__construct();
        $this->setType($type);
    }
}
