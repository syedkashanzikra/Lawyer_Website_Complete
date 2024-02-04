<?php

namespace Tests\YooKassa\Model;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;

/**
 * @internal
 */
class MonetaryAmountTest extends TestCase
{
    public const DEFAULT_CURRENCY = CurrencyCode::RUB;
    public const DEFAULT_VALUE = '0.00';

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     * @param mixed $currency
     */
    public function testConstructor($value, $currency): void
    {
        $instance = new MonetaryAmount();

        self::assertEquals(self::DEFAULT_VALUE, $instance->getValue());
        self::assertEquals(self::DEFAULT_CURRENCY, $instance->getCurrency());

        $instance = new MonetaryAmount($value, $currency);

        self::assertEquals(number_format($value, 2, '.', ''), $instance->getValue());
        self::assertEquals(strtoupper($currency), $instance->getCurrency());
    }

    /**
     * @dataProvider validArrayDataProvider
     *
     * @param mixed $data
     */
    public function testArrayConstructor($data): void
    {
        $instance = new MonetaryAmount();

        self::assertEquals(self::DEFAULT_VALUE, $instance->getValue());
        self::assertEquals(self::DEFAULT_CURRENCY, $instance->getCurrency());

        $instance = new MonetaryAmount($data);

        self::assertEquals(number_format($data['value'], 2, '.', ''), $instance->getValue());
        self::assertEquals(strtoupper($data['currency']), $instance->getCurrency());
    }

    /**
     * @dataProvider validValueDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetValue($value): void
    {
        $expected = number_format($value, 2, '.', '');

        $instance = self::getInstance();
        self::assertEquals(self::DEFAULT_VALUE, $instance->getValue());
        self::assertEquals(self::DEFAULT_VALUE, $instance->value);
        $instance->setValue($value);
        self::assertEquals($expected, $instance->getValue());
        self::assertEquals($expected, $instance->value);

        $instance = self::getInstance();
        $instance->value = $value;
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

        try {
            $instance->setValue($value);
        } catch (Exception $e) {
            self::assertInstanceOf($exceptionClassName, $e);
        }
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidValue($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        try {
            $instance->value = $value;
        } catch (Exception $e) {
            self::assertInstanceOf($exceptionClassName, $e);
        }
    }

    /**
     * @dataProvider validCurrencyDataProvider
     */
    public function testGetSetCurrency(string $currency): void
    {
        $instance = self::getInstance();

        self::assertEquals(self::DEFAULT_CURRENCY, $instance->getCurrency());
        self::assertEquals(self::DEFAULT_CURRENCY, $instance->currency);
        $instance->setCurrency($currency);
        self::assertEquals(strtoupper($currency), $instance->getCurrency());
        self::assertEquals(strtoupper($currency), $instance->currency);
    }

    /**
     * @dataProvider invalidCurrencyDataProvider
     *
     * @param mixed $currency
     */
    public function testSetInvalidCurrency($currency, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->setCurrency($currency);
    }

    /**
     * @dataProvider invalidCurrencyDataProvider
     */
    public function testSetterInvalidCurrency(mixed $currency, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->currency = $currency;
    }

    public function validDataProvider(): array
    {
        $result = self::validValueDataProvider();
        foreach (self::validCurrencyDataProvider() as $index => $tmp) {
            if (isset($result[$index])) {
                $result[$index][] = $tmp[0];
            }
        }

        return $result;
    }

    public static function validArrayDataProvider(): array
    {
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'value' => Random::float(0, 9999.99),
                'currency' => Random::value(CurrencyCode::getValidValues()),
            ];
        }

        return $result;
    }

    public static function validValueDataProvider(): array
    {
        $result = [
            [0.01],
            [0.1],
            ['0.1'],
            [0.11],
            [0.112],
            [0.1111],
            [0.1166],
            ['0.01'],
            [1],
            ['1'],
        ];
        for ($i = 0, $iMax = count(CurrencyCode::getValidValues()) - count($result); $i < $iMax; $i++) {
            $result[] = [number_format(Random::float(0, 9999999), 2, '.', '')];
        }

        return $result;
    }

    public static function validCurrencyDataProvider(): array
    {
        $result = [];
        foreach (CurrencyCode::getValidValues() as $value) {
            $result[] = [$value];
        }
        return $result;
    }

    public static function invalidValueDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [[], InvalidPropertyValueTypeException::class],
            [fopen(__FILE__, 'rb'), InvalidPropertyValueTypeException::class],
            ['invalid_value', InvalidPropertyValueTypeException::class],
            [-1, InvalidPropertyValueException::class],
            [-0.01, InvalidPropertyValueException::class],
            [0.0, InvalidPropertyValueException::class],
            [0, InvalidPropertyValueException::class],
            [0.001, InvalidPropertyValueException::class],
            [true, InvalidPropertyValueTypeException::class],
            [false, InvalidPropertyValueTypeException::class],
        ];
    }

    public static function invalidCurrencyDataProvider(): array
    {
        return [
            ['', EmptyPropertyValueException::class],
            ['invalid_value', InvalidPropertyValueException::class],
            ['III', InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validMultiplyDataProvider
     *
     * @param mixed $source
     * @param mixed $coefficient
     * @param mixed $expected
     */
    public function testMultiply($source, $coefficient, $expected): void
    {
        $instance = new MonetaryAmount($source);
        $instance->multiply($coefficient);
        self::assertEquals($expected, $instance->getIntegerValue());
    }

    public static function validMultiplyDataProvider(): array
    {
        return [
            [1, 0.5, 50],
            [1.01, 0.5, 51],
            [1.00, 0.01, 1],
            [0.99, 0.01, 1],
        ];
    }

    /**
     * @dataProvider invalidMultiplyDataProvider
     *
     * @param mixed $source
     * @param mixed $coefficient
     */
    public function testInvalidMultiply($source, $coefficient): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new MonetaryAmount($source);
        $instance->multiply($coefficient);
    }

    public static function invalidMultiplyDataProvider(): array
    {
        return [
            [0.99, false],
            [0.99, -1.0],
            [0.99, -0.0],
            [0.99, -0.00001],
            [0.99, 0.000001],
        ];
    }

    /**
     * @dataProvider validIncreaseDataProvider
     *
     * @param mixed $source
     * @param mixed $amount
     * @param mixed $expected
     */
    public function testIncrease($source, $amount, $expected): void
    {
        $instance = new MonetaryAmount($source);
        $instance->increase($amount);
        self::assertEquals($expected, $instance->getIntegerValue());
    }

    public static function validIncreaseDataProvider(): array
    {
        return [
            [1.00, -0.001, 100],
        ];
    }

    /**
     * @dataProvider invalidIncreaseDataProvider
     *
     * @param mixed $source
     * @param mixed $amount
     */
    public function testInvalidIncrease($source, $amount): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new MonetaryAmount($source);
        $instance->increase($amount);
    }

    public static function invalidIncreaseDataProvider(): array
    {
        return [
            [0.99, -1.0],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     * @param mixed $currency
     */
    public function testJsonSerialize($value, $currency): void
    {
        $instance = new MonetaryAmount($value, $currency);
        $expected = [
            'value' => number_format($value, 2, '.', ''),
            'currency' => strtoupper($currency),
        ];
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    protected static function getInstance($value = null, $currency = null): MonetaryAmount
    {
        return new MonetaryAmount($value, $currency);
    }
}
