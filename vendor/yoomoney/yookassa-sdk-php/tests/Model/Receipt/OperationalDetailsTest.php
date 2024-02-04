<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\OperationalDetails;

/**
 * @internal
 */
class OperationalDetailsTest extends TestCase
{
    /**
     * @dataProvider validArrayDataProvider
     *
     * @param mixed $options
     */
    public function testConstructor($options): void
    {
        $instance = self::getInstance($options);

        self::assertEquals($options['operation_id'], $instance->getOperationId());
        self::assertEquals($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
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
    public function testGetSetOperationId(array $options): void
    {
        $instance = self::getInstance();

        $instance->setOperationId($options['operation_id']);
        self::assertEquals($options['operation_id'], $instance->getOperationId());
        self::assertEquals($options['operation_id'], $instance->operation_id);
    }

    /**
     * @dataProvider invalidOperationIdDataProvider
     */
    public function testSetInvalidOperationId(mixed $operation_id, string $exceptionClassOperationId): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassOperationId);
        $instance->setOperationId($operation_id);
    }

    /**
     * @dataProvider invalidOperationIdDataProvider
     */
    public function testSetterInvalidOperationId(mixed $operation_id, string $exceptionClassOperationId): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassOperationId);
        $instance->operation_id = $operation_id;
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testGetSetCreatedAt(array $options): void
    {
        $instance = self::getInstance();

        $instance->setCreatedAt($options['created_at']);
        self::assertEquals($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertEquals($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));
    }

    /**
     * @dataProvider invalidCreatedAtDataProvider
     */
    public function testSetInvalidCreatedAt(mixed $created_at, string $exceptionClassCreatedAt): void
    {
        $instance = self::getInstance();
        self::expectException($exceptionClassCreatedAt);
        $instance->setCreatedAt($created_at);
    }

    /**
     * @dataProvider invalidCreatedAtDataProvider
     */
    public function testSetterInvalidCreatedAt(mixed $created_at, string $exceptionClassCreatedAt): void
    {
        $instance = self::getInstance();

        self::expectException($exceptionClassCreatedAt);
        $instance->created_at = $created_at;
    }

    public static function validArrayDataProvider()
    {
        date_default_timezone_set('UTC');
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'operation_id' => Random::int(1, OperationalDetails::OPERATION_ID_MAX_VALUE),
                'created_at' => date(YOOKASSA_DATE, Random::int(10000000, 29999999)),
                'value' => Random::str(1, OperationalDetails::VALUE_MAX_LENGTH),
            ];
        }

        return $result;
    }

    public static function invalidValueDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(OperationalDetails::VALUE_MAX_LENGTH + 1), InvalidPropertyValueException::class],
        ];
    }

    public static function invalidOperationIdDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', TypeError::class],
            [Random::str(OperationalDetails::MIN_VALUE, OperationalDetails::OPERATION_ID_MAX_VALUE + 1), TypeError::class],
            [Random::int(OperationalDetails::OPERATION_ID_MAX_VALUE + 1), InvalidPropertyValueException::class],
        ];
    }

    public static function invalidCreatedAtDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [[], TypeError::class],
            [fopen(__FILE__, 'r'), TypeError::class],
            ['III', InvalidPropertyValueException::class],
            [-0.01, InvalidPropertyValueException::class],
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
        return new OperationalDetails($options);
    }
}
