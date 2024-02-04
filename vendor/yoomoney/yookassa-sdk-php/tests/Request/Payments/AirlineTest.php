<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\Airline;
use YooKassa\Request\Payments\Leg;
use YooKassa\Request\Payments\LegInterface;
use YooKassa\Request\Payments\Passenger;
use YooKassa\Request\Payments\PassengerInterface;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class AirlineTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $data
     */
    public function testAirlineInstantiate($data): void
    {
        $airline = new Airline();

        self::assertFalse($airline->notEmpty());

        $airline->setBookingReference($data['booking_reference']);
        $airline->setTicketNumber($data['ticket_number']);
        $airline->setPassengers($data['passengers']);
        $airline->setLegs($data['legs']);

        self::assertEquals($airline->getBookingReference(), $data['booking_reference']);
        self::assertEquals($airline->getTicketNumber(), $data['ticket_number']);
        self::assertIsArray($airline->getPassengers()->getItems()->toArray());
        self::assertIsArray($airline->getLegs()->getItems()->toArray());

        foreach ($airline->getLegs() as $leg) {
            self::assertInstanceOf(LegInterface::class, $leg);
        }

        foreach ($airline->getPassengers() as $passenger) {
            self::assertInstanceOf(PassengerInterface::class, $passenger);
        }

        self::assertTrue($airline->notEmpty());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $data
     */
    public function testFromArrayInstantiate($data): void
    {
        $airline = new Airline($data);

        self::assertEquals($airline->getBookingReference(), $data['booking_reference']);
        self::assertEquals($airline->getTicketNumber(), $data['ticket_number']);
        self::assertIsArray($airline->getPassengers()->getItems()->toArray());
        self::assertIsArray($airline->getLegs()->getItems()->toArray());

        foreach ($airline->getLegs() as $leg) {
            self::assertInstanceOf(LegInterface::class, $leg);
        }

        foreach ($airline->getPassengers() as $passenger) {
            self::assertInstanceOf(PassengerInterface::class, $passenger);
        }

        self::assertTrue($airline->notEmpty());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $data
     */
    public function testAddLeg($data): void
    {
        $airline = new Airline();

        foreach ($data['legs'] as $leg) {
            $airline->getLegs()->add($leg);
        }

        foreach ($airline->getLegs() as $leg) {
            self::assertInstanceOf(LegInterface::class, $leg);
        }

        self::assertTrue($airline->notEmpty());
    }

    /**
     * @dataProvider exceptionDataProvider
     *
     * @param mixed $data
     */
    public function testAirlinePassengersDataValidate($data): void
    {
        $airline = new Airline();

        $this->expectException($data['exception']);

        $airline->setPassengers($data['value']);
    }

    /**
     * @dataProvider exceptionDataProvider
     *
     * @param mixed $data
     */
    public function testAirlineLegsDataValidate($data): void
    {
        $airline = new Airline();

        $this->expectException($data['exception']);

        $airline->setLegs($data['value']);
    }

    /**
     * @dataProvider stringsExceptionDataProvider
     *
     * @param mixed $data
     */
    public function testBookingReferenceValidate($data): void
    {
        $airline = new Airline();

        $this->expectException($data['exception']);

        $airline->setBookingReference($data['value']);
    }

    /**
     * @dataProvider stringsExceptionDataProvider
     *
     * @param mixed $data
     * @throws Exception
     */
    public function testTicketNumberValidate($data): void
    {
        $airline = new Airline();

        $this->expectException($data['exception']);

        $airline->setTicketNumber($data['value']);
    }

    public static function validDataProvider()
    {
        $passenger = new Passenger();
        $passenger->setFirstName('SERGEI');
        $passenger->setLastName('IVANOV');

        $leg = new Leg();
        $leg->setDepartureAirport('LED');
        $leg->setDestinationAirport('AMS');
        $leg->setDepartureDate('2018-06-20');

        return [
            [
                [
                    'booking_reference' => 'IIIKRV',
                    'ticket_number' => '12342123413',
                    'passengers' => [
                        [
                            'first_name' => 'SERGEI',
                            'last_name' => 'IVANOV',
                        ],
                    ],
                    'legs' => [
                        [
                            'departure_airport' => 'LED',
                            'destination_airport' => 'AMS',
                            'departure_date' => '2018-06-20',
                        ],
                    ],
                ],
            ],
            [
                [
                    'booking_reference' => '123',
                    'ticket_number' => '321',
                    'passengers' => [
                        $passenger,
                    ],
                    'legs' => [
                        $leg,
                    ],
                ],
            ],
        ];
    }

    public static function exceptionDataProvider()
    {
        return [
            [
                [
                    'exception' => \InvalidArgumentException::class,
                    'value' => new \stdClass(),
                ],
            ],
        ];
    }

    public static function stringsExceptionDataProvider()
    {
        return [
            [
                [
                    'exception' => InvalidPropertyValueException::class,
                    'value' => Random::str(151, 160),
                ],
            ],
        ];
    }
}
