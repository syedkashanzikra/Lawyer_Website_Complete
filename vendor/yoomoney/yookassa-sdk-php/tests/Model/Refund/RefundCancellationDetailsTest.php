<?php

namespace Tests\YooKassa\Model\Refund;

use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Model\Refund\RefundCancellationDetails;
use YooKassa\Model\Refund\RefundCancellationDetailsPartyCode;
use YooKassa\Model\Refund\RefundCancellationDetailsReasonCode;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class RefundCancellationDetailsTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed|null $data
     */
    public function testConstructor(mixed $data = null): void
    {
        $instance = self::getInstance($data);

        self::assertEquals($data['party'], $instance->getParty());
        self::assertEquals($data['reason'], $instance->getReason());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed|null $data
     */
    public function testGetSetParty(mixed $data = null): void
    {
        $instance = self::getInstance($data);
        self::assertEquals($data['party'], $instance->getParty());

        $instance = self::getInstance();
        $instance->setParty($data['party']);
        self::assertEquals($data['party'], $instance->getParty());
        self::assertEquals($data['party'], $instance->party);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed|null $data
     */
    public function testGetSetReason(mixed $data = null): void
    {
        $instance = self::getInstance($data);
        self::assertEquals($data['reason'], $instance->getReason());

        $instance = self::getInstance();
        $instance->setReason($data['reason']);
        self::assertEquals($data['reason'], $instance->getReason());
        self::assertEquals($data['reason'], $instance->reason);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidParty($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassName);
        $instance->setParty($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidReason($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->reason = $value;
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $cancellationDetailsParties = RefundCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = RefundCancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);
        for ($i = 0; $i < 20; $i++) {
            $result[] = [
                [
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ]
            ];
        }

        return $result;
    }

    public static function invalidValueDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [[], TypeError::class],
            [fopen(__FILE__, 'r'), TypeError::class],
            [true, InvalidPropertyValueException::class],
            [false, EmptyPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed|null $data
     */
    public function testJsonSerialize(mixed $data = null): void
    {
        $instance = new RefundCancellationDetails($data);
        $expected = $data;
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    /**
     * @param mixed|null $data
     */
    protected static function getInstance(mixed $data = null): RefundCancellationDetails
    {
        return new RefundCancellationDetails($data);
    }
}
