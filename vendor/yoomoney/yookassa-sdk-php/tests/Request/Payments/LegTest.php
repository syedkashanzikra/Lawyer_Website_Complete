<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Request\Payments\Leg;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class LegTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $data
     */
    public function testGettersSetters($data): void
    {
        $leg = new Leg();
        $leg->setDepartureAirport($data['departure_airport']);
        $leg->setDestinationAirport($data['destination_airport']);
        $leg->setDepartureDate($data['departure_date']);
        $leg->setCarrierCode($data['carrier_code']);

        self::assertEquals($data['departure_airport'], $leg->getDepartureAirport());
        self::assertEquals($data['destination_airport'], $leg->getDestinationAirport());
        self::assertEquals($data['carrier_code'], $leg->getCarrierCode());
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $data
     */
    public function testDepartureAirportValidate($data): void
    {
        $leg = new Leg();

        $this->expectException($data['exception']);

        $leg->setDepartureAirport($data['value']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $data
     */
    public function testDestinationAirportValidate($data): void
    {
        $leg = new Leg();

        $this->expectException($data['exception']);

        $leg->setDestinationAirport($data['value']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $data
     * @throws Exception
     */
    public function testDepartureDateValidate($data): void
    {
        $leg = new Leg();
        $this->expectException($data['exception']);
        $leg->setDepartureDate($data['value']);
    }

    public static function validDataProvider()
    {
        return [
            [
                [
                    'departure_airport' => 'LED',
                    'destination_airport' => 'AMS',
                    'departure_date' => '2018-06-20',
                    'carrier_code' => 'AN',
                ],
            ],
            [
                [
                    'departure_airport' => 'UGR',
                    'destination_airport' => 'IVA',
                    'departure_date' => '2018-06-21',
                    'carrier_code' => 'TW',
                ],
            ],
        ];
    }

    public static function invalidDataProvider()
    {
        return [
            [
                [
                    'exception' => InvalidPropertyValueException::class,
                    'value' => 'stringThatGreaterThanNeededCharsLongAndActuallyNotValidAtAll123',
                ],
            ],
        ];
    }
}
