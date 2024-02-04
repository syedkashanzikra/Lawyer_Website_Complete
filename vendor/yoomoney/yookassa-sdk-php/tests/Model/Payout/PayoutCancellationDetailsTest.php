<?php

namespace Tests\YooKassa\Model\Payout;

use PHPUnit\Framework\TestCase;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\PayoutCancellationDetails;
use YooKassa\Model\Payout\PayoutCancellationDetailsPartyCode;
use YooKassa\Model\Payout\PayoutCancellationDetailsReasonCode;

/**
 * @internal
 */
class PayoutCancellationDetailsTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param null|mixed $value
     */
    public function testConstructor(mixed $value = null): void
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
    public function testGetSetParty(mixed $value = null): void
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
    public function testGetSetReason(mixed $value = null): void
    {
        $instance = self::getInstance($value);
        self::assertEquals($value['reason'], $instance->getReason());

        $instance = self::getInstance([]);
        $instance->setReason($value['reason']);
        self::assertEquals($value['reason'], $instance->getReason());
        self::assertEquals($value['reason'], $instance->reason);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidParty(mixed $value, string $exceptionClassName): void
    {
        $instance = self::getInstance([]);

        $this->expectException($exceptionClassName);
        $instance->setParty($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidParty(mixed $value, string $exceptionClassName): void
    {
        $instance = self::getInstance([]);

        $this->expectException($exceptionClassName);
        $instance->party = $value;
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidReason(mixed $value, string $exceptionClassName): void
    {
        $instance = self::getInstance([]);

        $this->expectException($exceptionClassName);
        $instance->setReason($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidReason(mixed $value, string $exceptionClassName): void
    {
        $instance = self::getInstance([]);

        $this->expectException($exceptionClassName);
        $instance->reason = $value;
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $cancellationDetailsParties = PayoutCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = PayoutCancellationDetailsReasonCode::getValidValues();
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

    public static function invalidValueDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(10), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed|null $value
     */
    public function testJsonSerialize(mixed $value = null): void
    {
        $instance = new PayoutCancellationDetails($value);
        $expected = [
            'party' => $value['party'],
            'reason' => $value['reason'],
        ];
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    /**
     * @param mixed $value
     * @return PayoutCancellationDetails
     */
    protected static function getInstance(mixed $value = null): PayoutCancellationDetails
    {
        return new PayoutCancellationDetails($value);
    }
}
