<?php

namespace Tests\YooKassa\Model\Notification;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\DealInterface;
use YooKassa\Model\Notification\AbstractNotification;
use YooKassa\Model\Payment\PaymentInterface;
use YooKassa\Model\Payout\PayoutInterface;
use YooKassa\Model\Refund\RefundInterface;

abstract class AbstractTestNotification extends TestCase
{
    abstract public function validDataProvider(): array;

    /**
     * @dataProvider validDataProvider
     */
    public function testGetType(array $value): void
    {
        $instance = $this->getTestInstance($value);
        self::assertEquals($this->getExpectedType(), $instance->getType());
    }

    /**
     * @dataProvider invalidConstructorTypeDataProvider
     */
    public function testInvalidTypeInConstructor(array $source): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance($source);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetEvent(array $value): void
    {
        $instance = $this->getTestInstance($value);
        self::assertEquals($this->getExpectedEvent(), $instance->getEvent());
    }

    /**
     * @dataProvider invalidConstructorEventDataProvider
     */
    public function testInvalidEventInConstructor(array $source): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance($source);
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestNotification($value, $this->getExpectedEvent());
    }

    /**
     * @dataProvider invalidEventDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidEvent($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TestNotification($this->getExpectedType(), $value);
    }

    public function invalidConstructorTypeDataProvider()
    {
        return [
            [['event' => $this->getExpectedEvent(), 'type' => 'test']],
            [['event' => $this->getExpectedEvent(), 'type' => null]],
            [['event' => $this->getExpectedEvent(), 'type' => '']],
            [['event' => $this->getExpectedEvent(), 'type' => 1]],
            [['event' => $this->getExpectedEvent(), 'type' => []]],
        ];
    }

    public function invalidConstructorEventDataProvider()
    {
        return [
            [['type' => $this->getExpectedType(), 'event' => 'test']],
            [['type' => $this->getExpectedType(), 'event' => null]],
            [['type' => $this->getExpectedType(), 'event' => '']],
            [['type' => $this->getExpectedType(), 'event' => 1]],
            [['type' => $this->getExpectedType(), 'event' => []]],
        ];
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

    public static function invalidEventDataProvider()
    {
        return [
            [''],
            [null],
            [Random::str(40)],
            [0],
        ];
    }

    abstract protected function getTestInstance(array $source): AbstractNotification;

    abstract protected function getExpectedType(): string;

    abstract protected function getExpectedEvent(): string;
}

class TestNotification extends AbstractNotification
{
    public function __construct($type, $event)
    {
        parent::__construct();
        $this->setType($type);
        $this->setEvent($event);
    }

    public function getObject(): PaymentInterface|RefundInterface|PayoutInterface|DealInterface|null
    {
        return null;
    }
}
