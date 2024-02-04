<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\AdditionalUserProps;

/**
 * @internal
 */
class AdditionalUserPropsTest extends TestCase
{
    /**
     * @dataProvider validArrayDataProvider
     *
     * @param mixed $options
     */
    public function testConstructor($options): void
    {
        $instance = self::getInstance($options);

        self::assertEquals($options['value'], $instance->getValue());
        self::assertEquals($options['name'], $instance->getName());
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetValue(array $options): void
    {
        $expected = $options['value'];

        $instance = self::getInstance();

        $instance->setValue($expected);
        self::assertEquals($expected, $instance->getValue());
        self::assertEquals($expected, $instance->value);

        $instance = self::getInstance();
        $instance->value = $expected;
        self::assertEquals($expected, $instance->getValue());
        self::assertEquals($expected, $instance->value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidValue($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassName);
        $instance->setValue($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidValue($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassName);
        $instance->value = $value;
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetName(array $options): void
    {
        $instance = self::getInstance();

        $instance->setName($options['name']);
        self::assertEquals($options['name'], $instance->getName());
        self::assertEquals($options['name'], $instance->name);
    }

    /**
     * @dataProvider invalidNameDataProvider
     */
    public function testSetInvalidName(mixed $name, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassName);
        $instance->setName($name);
    }

    /**
     * @dataProvider invalidNameDataProvider
     */
    public function testSetterInvalidName(mixed $name, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassName);
        $instance->name = $name;
    }

    public static function validArrayDataProvider()
    {
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'value' => Random::str(1, AdditionalUserProps::VALUE_MAX_LENGTH),
                'name' => Random::str(1, AdditionalUserProps::NAME_MAX_LENGTH),
            ];
        }

        return $result;
    }

    public static function invalidValueDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(AdditionalUserProps::VALUE_MAX_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    public static function invalidNameDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(AdditionalUserProps::NAME_MAX_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    public function invalidIncreaseDataProvider()
    {
        return [
            [1, null],
            [1.01, ''],
            [1.00, true],
            [0.99, false],
            [0.99, []],
            [0.99, new stdClass()],
            [0.99, 'test'],
            [0.99, -1.0],
            [0.99, -0.99],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testJsonSerialize(array $options): void
    {
        $instance = self::getInstance($options);
        $expected = $options;
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    protected static function getInstance($options = [])
    {
        return new AdditionalUserProps($options);
    }
}
