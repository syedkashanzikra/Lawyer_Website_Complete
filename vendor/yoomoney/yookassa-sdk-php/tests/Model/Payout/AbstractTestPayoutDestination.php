<?php

namespace Tests\YooKassa\Model\Payout;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\AbstractPayoutDestination;

abstract class AbstractTestPayoutDestination extends TestCase
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

    abstract protected function getTestInstance(): AbstractPayoutDestination;

    abstract protected function getExpectedType(): string;
}

class TestPaymentData extends AbstractPayoutDestination
{
    public function __construct($type)
    {
        parent::__construct();
        $this->setType($type);
    }
}
