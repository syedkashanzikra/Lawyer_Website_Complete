<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\PaymentData\AbstractPaymentData;

abstract class AbstractTestPaymentData extends TestCase
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
        new TestPaymentData($value);
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

    abstract protected function getTestInstance(): AbstractPaymentData;

    abstract protected function getExpectedType(): string;
}

class TestPaymentData extends AbstractPaymentData
{
    public function __construct($type)
    {
        parent::__construct([]);
        $this->setType($type);
    }
}
