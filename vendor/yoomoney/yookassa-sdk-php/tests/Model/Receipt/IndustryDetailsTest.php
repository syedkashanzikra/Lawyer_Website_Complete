<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\IndustryDetails;

/**
 * @internal
 */
class IndustryDetailsTest extends TestCase
{
    /**
     * @dataProvider validArrayDataProvider
     *
     * @param mixed $options
     */
    public function testConstructor($options): void
    {
        $instance = self::getInstance($options);

        self::assertEquals($options['federal_id'], $instance->getFederalId());
        self::assertEquals($options['document_number'], $instance->getDocumentNumber());
        self::assertEquals($options['document_date'], $instance->getDocumentDate()->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
        self::assertEquals($options['value'], $instance->getValue());
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
    public function testSetInvalidValue($value, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->setValue($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidValue($value, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->value = $value;
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetDocumentNumber(array $options): void
    {
        $instance = self::getInstance();

        $instance->setDocumentNumber($options['document_number']);
        self::assertEquals($options['document_number'], $instance->getDocumentNumber());
        self::assertEquals($options['document_number'], $instance->document_number);
    }

    /**
     * @dataProvider invalidDocumentNumberDataProvider
     */
    public function testSetInvalidDocumentNumber(mixed $document_number, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->setDocumentNumber($document_number);
    }

    /**
     * @dataProvider invalidDocumentNumberDataProvider
     */
    public function testSetterInvalidDocumentNumber(mixed $document_number, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->document_number = $document_number;
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetFederalId(array $options): void
    {
        $instance = self::getInstance();

        $instance->setFederalId($options['federal_id']);
        self::assertEquals($options['federal_id'], $instance->getFederalId());
        self::assertEquals($options['federal_id'], $instance->federal_id);
    }

    /**
     * @dataProvider invalidFederalIdDataProvider
     */
    public function testSetInvalidFederalId(mixed $federal_id, string $exceptionClassFederalId): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassFederalId);
        $instance->setFederalId($federal_id);
    }

    /**
     * @dataProvider invalidFederalIdDataProvider
     */
    public function testSetterInvalidFederalId(mixed $federal_id, string $exceptionClassFederalId): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassFederalId);
        $instance->federal_id = $federal_id;
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetDocumentDate(array $options): void
    {
        $instance = self::getInstance();

        $instance->setDocumentDate($options['document_date']);
        self::assertEquals($options['document_date'], $instance->getDocumentDate()->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
        self::assertEquals($options['document_date'], $instance->document_date->format(IndustryDetails::DOCUMENT_DATE_FORMAT));
    }

    /**
     * @dataProvider invalidDocumentDateDataProvider
     */
    public function testSetInvalidDocumentDate(mixed $document_date, string $exceptionClassDocumentDate): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentDate);
        $instance->setDocumentDate($document_date);
    }

    /**
     * @dataProvider invalidDocumentDateDataProvider
     */
    public function testSetterInvalidDocumentDate(mixed $document_date, string $exceptionClassDocumentDate): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentDate);
        $instance->document_date = $document_date;
    }

    public static function validArrayDataProvider()
    {
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'federal_id' => Random::value([
                    '00' . Random::int(1, 9),
                    '0' . Random::int(1, 6) . Random::int(0, 9),
                    '07' . Random::int(0, 3)
                    ]),
                'document_date' => date(IndustryDetails::DOCUMENT_DATE_FORMAT, Random::int(10000000, 29999999)),
                'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
            ];
        }

        return $result;
    }

    public static function invalidValueDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(IndustryDetails::VALUE_MAX_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    public static function invalidDocumentNumberDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    public static function invalidFederalIdDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
        ];
    }

    public static function invalidDocumentDateDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [[], TypeError::class],
            [fopen(__FILE__, 'r'), TypeError::class],
            [Random::str(IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH + 1), InvalidPropertyValueException::class],
            [false, EmptyPropertyValueException::class],
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
        return new IndustryDetails($options);
    }
}
