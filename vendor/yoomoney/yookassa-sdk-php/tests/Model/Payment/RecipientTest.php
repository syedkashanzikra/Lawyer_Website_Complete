<?php

namespace Tests\YooKassa\Model\Payment;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\Payment\Recipient;

/**
 * @internal
 */
class RecipientTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetAccountId($value): void
    {
        $instance = new Recipient();

        $instance->setAccountId($value);
        self::assertEquals((string) $value, $instance->getAccountId());
        self::assertEquals((string) $value, $instance->accountId);
        self::assertEquals((string) $value, $instance->account_id);

        $instance = new Recipient();
        $instance->accountId = $value;
        self::assertEquals((string) $value, $instance->getAccountId());
        self::assertEquals((string) $value, $instance->accountId);
        self::assertEquals((string) $value, $instance->account_id);

        $instance = new Recipient();
        $instance->account_id = $value;
        self::assertEquals((string) $value, $instance->getAccountId());
        self::assertEquals((string) $value, $instance->accountId);
        self::assertEquals((string) $value, $instance->account_id);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAccountId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Recipient();
        $instance->setAccountId($value);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAccountId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Recipient();
        $instance->accountId = $value;
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeAccountId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Recipient();
        $instance->account_id = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetGatewayId($value): void
    {
        $instance = new Recipient();

        self::assertEquals(null, $instance->getGatewayId());
        self::assertEquals(null, $instance->gatewayId);
        self::assertEquals(null, $instance->gateway_id);
        $instance->setGatewayId($value);
        self::assertEquals((string) $value, $instance->getGatewayId());
        self::assertEquals((string) $value, $instance->gatewayId);
        self::assertEquals((string) $value, $instance->gateway_id);

        $instance = new Recipient();
        $instance->gatewayId = $value;
        self::assertEquals((string) $value, $instance->getGatewayId());
        self::assertEquals((string) $value, $instance->gatewayId);
        self::assertEquals((string) $value, $instance->gateway_id);

        $instance = new Recipient();
        $instance->gateway_id = $value;
        self::assertEquals((string) $value, $instance->getGatewayId());
        self::assertEquals((string) $value, $instance->gatewayId);
        self::assertEquals((string) $value, $instance->gateway_id);
    }

    public static function validDataProvider()
    {
        return [
            [Random::str(1)],
            [Random::str(2, 64)],
            [new StringObject(Random::str(2, 32))],
            [123],
        ];
    }

    public static function invalidDataProvider()
    {
        return [
            [null],
        ];
    }
}
