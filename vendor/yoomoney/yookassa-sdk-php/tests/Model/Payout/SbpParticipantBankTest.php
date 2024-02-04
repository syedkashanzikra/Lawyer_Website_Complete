<?php

namespace Tests\YooKassa\Model\Payout;

use Exception;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\SbpParticipantBank;
use PHPUnit\Framework\TestCase;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

class SbpParticipantBankTest extends TestCase
{
    /**
     * @param array $options
     * @return SbpParticipantBank
     */
    protected static function getInstance(array $options = []): SbpParticipantBank
    {
        return new SbpParticipantBank($options);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $value
     * @return void
     */
    public function testGetSetName(array $value): void
    {
        $instance = self::getInstance();
        $instance->setName($value['name']);
        self::assertEquals($value['name'], $instance->getName());
        self::assertEquals($value['name'], $instance->name);

        $instance->name = $value['name'];
        self::assertEquals($value['name'], $instance->getName());
        self::assertEquals($value['name'], $instance->name);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $value
     * @return void
     */
    public function testGetSetBic(array $value): void
    {
        $instance = self::getInstance();
        $instance->setBic($value['bic']);
        self::assertEquals($value['bic'], $instance->getBic());
        self::assertEquals($value['bic'], $instance->bic);

        $instance->bic = $value['bic'];
        self::assertEquals($value['bic'], $instance->getBic());
        self::assertEquals($value['bic'], $instance->bic);
    }

    /**
     * @dataProvider validDataProvider
     * @param array $value
     * @return void
     */
    public function testGetSetBankId(array $value): void
    {
        $instance = self::getInstance();
        $instance->setBankId($value['bank_id']);
        self::assertEquals($value['bank_id'], $instance->getBankId());
        self::assertEquals($value['bank_id'], $instance->bank_id);

        $instance->bank_id = $value['bank_id'];
        self::assertEquals($value['bank_id'], $instance->getBankId());
        self::assertEquals($value['bank_id'], $instance->bank_id);
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

    /**
     * @dataProvider invalidNameDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidName(mixed $value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassName);
        $instance->setName($value);
    }

    /**
     * @dataProvider invalidNameDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidName(mixed $value, string $exceptionClassName): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassName);
        $instance->name = $value;
    }

    /**
     * @dataProvider invalidBankIdDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassBankId
     */
    public function testSetInvalidBankId(mixed $value, string $exceptionClassBankId): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassBankId);
        $instance->setBankId($value);
    }

    /**
     * @dataProvider invalidBankIdDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassBankId
     */
    public function testSetterInvalidBankId(mixed $value, string $exceptionClassBankId): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassBankId);
        $instance->bank_id = $value;
    }

    /**
     * @dataProvider invalidBicDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassBic
     */
    public function testSetInvalidBic(mixed $value, string $exceptionClassBic): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassBic);
        $instance->setBic($value);
    }

    /**
     * @dataProvider invalidBicDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassBic
     */
    public function testSetterInvalidBic(mixed $value, string $exceptionClassBic): void
    {
        $instance = self::getInstance();

        $this->expectException($exceptionClassBic);
        $instance->bic = $value;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $sbpParticipantBank = [];
        for ($i = 0; $i < 10; $i++) {
            $array = [
                'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                'bic' => Random::str(9, 9, '0123456789'),
            ];
            $sbpParticipantBank[] = [$array];
        }

        return $sbpParticipantBank;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validArrayDataProvider(): array
    {
        $result = [];
        foreach (range(1, 10) as $i) {
            $result[$i][] = [
                'bank_id' => Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID),
                'name' => Random::str(SbpParticipantBank::MAX_LENGTH_NAME),
                'bic' => Random::str(9, 9, '0123456789'),
            ];
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidNameDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(SbpParticipantBank::MAX_LENGTH_NAME + 1), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidBankIdDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(SbpParticipantBank::MAX_LENGTH_BANK_ID + 1), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidBicDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [true, InvalidPropertyValueException::class],
        ];
    }
}
