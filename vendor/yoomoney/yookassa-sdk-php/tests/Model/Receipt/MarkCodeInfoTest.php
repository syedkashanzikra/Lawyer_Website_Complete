<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\MarkCodeInfo;

/**
 * @internal
 */
class MarkCodeInfoTest extends TestCase
{
    /**
     * @dataProvider validArrayDataProvider
     *
     * @param mixed $options
     */
    public function testConstructor($options): void
    {
        $instance = self::getInstance($options);

        self::assertEquals($options['mark_code_raw'], $instance->getMarkCodeRaw());
        self::assertEquals($options['unknown'], $instance->getUnknown());
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetMarkCodeRaw(array $options): void
    {
        $expected = $options['mark_code_raw'];

        $instance = self::getInstance();

        $instance->setMarkCodeRaw($expected);
        self::assertEquals($expected, $instance->getMarkCodeRaw());
        self::assertEquals($expected, $instance->mark_code_raw);

        $instance = self::getInstance();
        $instance->mark_code_raw = $expected;
        self::assertEquals($expected, $instance->getMarkCodeRaw());
        self::assertEquals($expected, $instance->mark_code_raw);
    }

    /**
     * @dataProvider invalidMarkCodeRawDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMarkCodeRaw($value, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->setMarkCodeRaw($value);
    }

    /**
     * @dataProvider invalidMarkCodeRawDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidMarkCodeRaw($value, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->mark_code_raw = $value;
    }

    public static function invalidMarkCodeRawDataProvider()
    {
        return [
            [[], TypeError::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetUnknown(array $options): void
    {
        $expected = $options['unknown'];

        $instance = self::getInstance();

        $instance->setUnknown($expected);
        self::assertEquals($expected, $instance->getUnknown());
        self::assertEquals($expected, $instance->unknown);

        $instance = self::getInstance();
        $instance->unknown = $expected;
        self::assertEquals($expected, $instance->getUnknown());
        self::assertEquals($expected, $instance->unknown);
    }

    /**
     * @dataProvider invalidUnknownDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidUnknown($value, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->setUnknown($value);
    }

    /**
     * @dataProvider invalidUnknownDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidUnknown($value, string $exceptionClassDocumentNumber): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassDocumentNumber);
        $instance->unknown = $value;
    }

    public static function invalidUnknownDataProvider()
    {
        return [
            [[], TypeError::class],
            [fopen(__FILE__, 'r'), TypeError::class],
            [Random::str(MarkCodeInfo::MAX_UNKNOWN_LENGTH + 1), InvalidPropertyValueException::class]
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetEan8(array $options): void
    {
        $expected = $options['ean_8'];

        $instance = self::getInstance();

        $instance->setEan8($expected);
        self::assertEquals($expected, $instance->getEan8());
        self::assertEquals($expected, $instance->ean_8);

        $instance = self::getInstance();
        $instance->ean_8 = $expected;
        self::assertEquals($expected, $instance->getEan8());
        self::assertEquals($expected, $instance->ean_8);
    }

    /**
     * @dataProvider invalidEan8DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidEan8($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setEan8($value);
    }

    /**
     * @dataProvider invalidEan8DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidEan8($value, string $exception): void
    {
        $instance = self::getInstance();

        self::expectException($exception);
        $instance->ean_8 = $value;
    }

    public static function invalidEan8DataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::EAN_8_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetEan13(array $options): void
    {
        $expected = $options['ean_13'];

        $instance = self::getInstance();

        $instance->setEan13($expected);
        self::assertEquals($expected, $instance->getEan13());
        self::assertEquals($expected, $instance->ean_13);

        $instance = self::getInstance();
        $instance->ean_13 = $expected;
        self::assertEquals($expected, $instance->getEan13());
        self::assertEquals($expected, $instance->ean_13);
    }

    /**
     * @dataProvider invalidEan13DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidEan13($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setEan13($value);
    }

    /**
     * @dataProvider invalidEan13DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidEan13($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->ean_13 = $value;
    }

    public static function invalidEan13DataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::EAN_13_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetItf14(array $options): void
    {
        $expected = $options['itf_14'];

        $instance = self::getInstance();

        $instance->setItf14($expected);
        self::assertEquals($expected, $instance->getItf14());
        self::assertEquals($expected, $instance->itf_14);

        $instance = self::getInstance();
        $instance->itf_14 = $expected;
        self::assertEquals($expected, $instance->getItf14());
        self::assertEquals($expected, $instance->itf_14);
    }

    /**
     * @dataProvider invalidItf14DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidItf14($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setItf14($value);
    }

    /**
     * @dataProvider invalidItf14DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidItf14($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->itf_14 = $value;
    }

    public static function invalidItf14DataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::ITF_14_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetGs10(array $options): void
    {
        $expected = $options['gs_10'];

        $instance = self::getInstance();

        $instance->setGs10($expected);
        self::assertEquals($expected, $instance->getGs10());
        self::assertEquals($expected, $instance->gs_10);

        $instance = self::getInstance();
        $instance->gs_10 = $expected;
        self::assertEquals($expected, $instance->getGs10());
        self::assertEquals($expected, $instance->gs_10);
    }

    /**
     * @dataProvider invalidGs10DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidGs10($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setGs10($value);
    }

    /**
     * @dataProvider invalidGs10DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidGs10($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->gs_10 = $value;
    }

    public static function invalidGs10DataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::MAX_GS_10_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetGs1m(array $options): void
    {
        $expected = $options['gs_1m'];

        $instance = self::getInstance();

        $instance->setGs1m($expected);
        self::assertEquals($expected, $instance->getGs1m());
        self::assertEquals($expected, $instance->gs_1m);

        $instance = self::getInstance();
        $instance->gs_1m = $expected;
        self::assertEquals($expected, $instance->getGs1m());
        self::assertEquals($expected, $instance->gs_1m);
    }

    /**
     * @dataProvider invalidGs1mDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidGs1m($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setGs1m($value);
    }

    /**
     * @dataProvider invalidGs1mDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidGs1m($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->gs_1m = $value;
    }

    public static function invalidGs1mDataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::MAX_GS_1M_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetShort(array $options): void
    {
        $expected = $options['short'];

        $instance = self::getInstance();

        $instance->setShort($expected);
        self::assertEquals($expected, $instance->getShort());
        self::assertEquals($expected, $instance->short);

        $instance = self::getInstance();
        $instance->short = $expected;
        self::assertEquals($expected, $instance->getShort());
        self::assertEquals($expected, $instance->short);
    }

    /**
     * @dataProvider invalidShortDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidShort($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setShort($value);
    }

    /**
     * @dataProvider invalidShortDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidShort($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->short = $value;
    }

    public static function invalidShortDataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::MAX_SHORT_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetFur(array $options): void
    {
        $expected = $options['fur'];

        $instance = self::getInstance();

        $instance->setFur($expected);
        self::assertEquals($expected, $instance->getFur());
        self::assertEquals($expected, $instance->fur);

        $instance = self::getInstance();
        $instance->fur = $expected;
        self::assertEquals($expected, $instance->getFur());
        self::assertEquals($expected, $instance->fur);
    }

    /**
     * @dataProvider invalidFurDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidFur($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setFur($value);
    }

    /**
     * @dataProvider invalidFurDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidFur($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->fur = $value;
    }

    public static function invalidFurDataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::FUR_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetEgais20(array $options): void
    {
        $expected = $options['egais_20'];

        $instance = self::getInstance();

        $instance->setEgais20($expected);
        self::assertEquals($expected, $instance->getEgais20());
        self::assertEquals($expected, $instance->egais_20);

        $instance = self::getInstance();
        $instance->egais_20 = $expected;
        self::assertEquals($expected, $instance->getEgais20());
        self::assertEquals($expected, $instance->egais_20);
    }

    /**
     * @dataProvider invalidEgais20DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidEgais20($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setEgais20($value);
    }

    /**
     * @dataProvider invalidEgais20DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidEgais20($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->egais_20 = $value;
    }

    public static function invalidEgais20DataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::EGAIS_20_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetEgais30(array $options): void
    {
        $expected = $options['egais_30'];

        $instance = self::getInstance();

        $instance->setEgais30($expected);
        self::assertEquals($expected, $instance->getEgais30());
        self::assertEquals($expected, $instance->egais_30);

        $instance = self::getInstance();
        $instance->egais_30 = $expected;
        self::assertEquals($expected, $instance->getEgais30());
        self::assertEquals($expected, $instance->egais_30);
    }

    /**
     * @dataProvider invalidEgais30DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidEgais30($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->setEgais30($value);
    }

    /**
     * @dataProvider invalidEgais30DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidEgais30($value, string $exception): void
    {
        $instance = self::getInstance();

        $this->expectException($exception);
        $instance->egais_30 = $value;
    }

    public static function invalidEgais30DataProvider()
    {
        return [
            [Random::str(MarkCodeInfo::EGAIS_30_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    public static function validArrayDataProvider()
    {
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'mark_code_raw' => Random::str(1, 256),
                'unknown' => Random::str(1, MarkCodeInfo::MAX_UNKNOWN_LENGTH),
                'ean_8' => Random::str(MarkCodeInfo::EAN_8_LENGTH),
                'ean_13' => Random::str(MarkCodeInfo::EAN_13_LENGTH),
                'itf_14' => Random::str(MarkCodeInfo::ITF_14_LENGTH),
                'gs_10' => Random::str(MarkCodeInfo::MAX_GS_10_LENGTH),
                'gs_1m' => Random::str(MarkCodeInfo::MAX_GS_1M_LENGTH),
                'short' => Random::str(MarkCodeInfo::MAX_SHORT_LENGTH),
                'fur' => Random::str(MarkCodeInfo::FUR_LENGTH),
                'egais_20' => Random::str(MarkCodeInfo::EGAIS_20_LENGTH),
                'egais_30' => Random::str(MarkCodeInfo::EGAIS_30_LENGTH),
            ];
        }

        return $result;
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
        return new MarkCodeInfo($options);
    }
}
