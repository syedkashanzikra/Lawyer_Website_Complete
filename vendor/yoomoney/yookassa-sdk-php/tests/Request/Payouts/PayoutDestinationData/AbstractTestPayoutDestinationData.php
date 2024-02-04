<?php

namespace Tests\YooKassa\Request\Payouts\PayoutDestinationData;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payouts\PayoutDestinationData\AbstractPayoutDestinationData;

abstract class AbstractTestPayoutDestinationData extends TestCase
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

    public static function invalidTypeDataProvider(): array
    {
        return [
            [''],
            [null],
            [Random::str(40)],
            [0],
        ];
    }

    abstract protected function getTestInstance(): AbstractPayoutDestinationData;

    abstract protected function getExpectedType(): string;
}

class TestPaymentData extends AbstractPayoutDestinationData
{
    public function __construct($type)
    {
        parent::__construct();
        $this->setType($type);
    }
}
