<?php

namespace Tests\YooKassa\Model\PersonalData;

use PHPUnit\Framework\TestCase;
use YooKassa\Common\Exceptions\EmptyPropertyValueException;
use YooKassa\Common\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetails;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsPartyCode;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsReasonCode;

/**
 * @internal
 */
class PersonalDataCancellationDetailsTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param null|mixed $value
     */
    public function testConstructor($value = null): void
    {
        $instance = self::getInstance($value);

        self::assertEquals($value['party'], $instance->getParty());
        self::assertEquals($value['reason'], $instance->getReason());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param null|mixed $value
     */
    public function testGetSetParty($value): void
    {
        $instance = self::getInstance($value);
        self::assertEquals($value['party'], $instance->getParty());

        $instance = self::getInstance([]);
        $instance->setParty($value['party']);
        self::assertEquals($value['party'], $instance->getParty());
        self::assertEquals($value['party'], $instance->party);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param null $value
     */
    public function testGetSetReason($value = null): void
    {
        $instance = self::getInstance($value);
        self::assertEquals($value['reason'], $instance->getReason());

        $instance = self::getInstance([]);
        $instance->setReason($value['reason']);
        self::assertEquals($value['reason'], $instance->getReason());
        self::assertEquals($value['reason'], $instance->reason);
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $cancellationDetailsParties = PersonalDataCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = PersonalDataCancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);
        for ($i = 0; $i < 20; $i++) {
            $result[] = [
                [
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ],
            ];
        }

        return $result;
    }

    public static function invalidValueDataProvider()
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [[], InvalidPropertyValueTypeException::class],
            [fopen(__FILE__, 'rb'), InvalidPropertyValueTypeException::class],
            [true, InvalidPropertyValueTypeException::class],
            [false, InvalidPropertyValueTypeException::class],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param null $value
     */
    public function testJsonSerialize($value = null): void
    {
        $instance = new PersonalDataCancellationDetails($value);
        $expected = [
            'party' => $value['party'],
            'reason' => $value['reason'],
        ];
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    /**
     * @param mixed $value
     * @return PersonalDataCancellationDetails
     */
    protected static function getInstance(mixed $value): PersonalDataCancellationDetails
    {
        return new PersonalDataCancellationDetails($value);
    }
}
