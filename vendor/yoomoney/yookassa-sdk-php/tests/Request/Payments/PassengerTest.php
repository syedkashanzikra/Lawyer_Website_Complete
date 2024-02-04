<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\Passenger;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class PassengerTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $name
     */
    public function testGetSetFirstName($name): void
    {
        $instance = self::getInstance();
        $instance->setFirstName($name);
        self::assertEquals($name, $instance->getFirstName());
        self::assertEquals($name, $instance->firstName);
        self::assertEquals($name, $instance->first_name);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $name
     */
    public function testGetSetLastName($name): void
    {
        $instance = self::getInstance();
        $instance->setLastName($name);
        self::assertEquals($name, $instance->getLastName());
        self::assertEquals($name, $instance->lastName);
        self::assertEquals($name, $instance->last_name);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     * @throws Exception
     */
    public function testSetInvalidFirstName($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassName);
        $instance->setFirstName($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     * @throws Exception
     */
    public function testSetterInvalidLastName($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->setLastName($value);
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        return [
            [
                'firstName' => Random::str(1, null, $alphabet),
                'lastName' => Random::str(1, null, $alphabet),
            ],
            [
                'firstName' => Random::str(64, null, $alphabet),
                'lastName' => Random::str(64, null, $alphabet),
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidValueDataProvider(): array
    {
        return [
            [Random::str(65), InvalidPropertyValueException::class],
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testJsonSerialize(string $firstName, string $lastName): void
    {
        $instance = self::getInstance();
        $instance->setFirstName($firstName);
        $instance->setLastName($lastName);

        $expected = [
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    protected static function getInstance(): Passenger
    {
        return new Passenger();
    }
}
