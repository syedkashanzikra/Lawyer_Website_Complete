<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\MarkQuantity;

/**
 * @internal
 */
class MarkQuantityTest extends TestCase
{
    /**
     * @dataProvider validArrayDataProvider
     *
     * @param mixed $options
     */
    public function testConstructor($options): void
    {
        $instance = self::getInstance($options);

        self::assertEquals($options['numerator'], $instance->getNumerator());
        self::assertEquals($options['denominator'], $instance->getDenominator());
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetDenominator(array $options): void
    {
        $expected = $options['denominator'];

        $instance = self::getInstance();

        $instance->setDenominator($expected);
        self::assertEquals($expected, $instance->getDenominator());
        self::assertEquals($expected, $instance->denominator);

        $instance = self::getInstance();
        $instance->denominator = $expected;
        self::assertEquals($expected, $instance->getDenominator());
        self::assertEquals($expected, $instance->denominator);
    }

    /**
     * @dataProvider invalidDenominatorDataProvider
     *
     * @param mixed $denominator
     */
    public function testSetInvalidDenominator($denominator, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassDocumentNumber);
        $instance->setDenominator($denominator);
    }

    /**
     * @dataProvider invalidDenominatorDataProvider
     *
     * @param mixed $denominator
     */
    public function testSetterInvalidDenominator($denominator, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassDocumentNumber);
        $instance->denominator = $denominator;
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetNumerator(array $options): void
    {
        $instance = self::getInstance();

        $instance->setNumerator($options['numerator']);
        self::assertEquals($options['numerator'], $instance->getNumerator());
        self::assertEquals($options['numerator'], $instance->numerator);
    }

    /**
     * @dataProvider invalidNumeratorDataProvider
     */
    public function testSetInvalidNumerator(mixed $numerator, string $exceptionClassNumerator): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassNumerator);
        $instance->setNumerator($numerator);
    }

    /**
     * @dataProvider invalidNumeratorDataProvider
     */
    public function testSetterInvalidNumerator(mixed $numerator, string $exceptionClassNumerator): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassNumerator);
        $instance->numerator = $numerator;
    }

    public static function validArrayDataProvider()
    {
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'numerator' => Random::int(MarkQuantity::MIN_VALUE, 100),
                'denominator' => Random::int(MarkQuantity::MIN_VALUE, 100),
            ];
        }

        return $result;
    }

    public static function invalidDenominatorDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            [Random::int(-100, MarkQuantity::MIN_VALUE - 1), InvalidPropertyValueException::class],
            [-1, InvalidPropertyValueException::class],
            [0.0, InvalidPropertyValueException::class],
            [0, InvalidPropertyValueException::class],
        ];
    }

    public static function invalidNumeratorDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            [Random::int(-100, MarkQuantity::MIN_VALUE - 1), InvalidPropertyValueException::class],
            [0.0, InvalidPropertyValueException::class],
            [0, InvalidPropertyValueException::class],
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
        return new MarkQuantity($options);
    }
}
