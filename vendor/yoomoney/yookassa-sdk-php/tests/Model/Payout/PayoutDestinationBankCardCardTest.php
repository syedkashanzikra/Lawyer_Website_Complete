<?php

namespace Tests\YooKassa\Model\Payout;

use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;
use YooKassa\Model\Payout\PayoutDestinationBankCardCard;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class PayoutDestinationBankCardCardTest extends TestCase
{
    /**
     * @dataProvider validLast4DataProvider
     */
    public function testGetSetLast4(string $value): void
    {
        $this->getAndSetTest($value, 'last4');
    }


    /**
     * @dataProvider validFirst6DataProvider
     */
    public function testGetSetFirst6(string $value): void
    {
        $this->getAndSetTest($value, 'first6');
    }


    /**
     * @dataProvider validCardTypeDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCardType($value): void
    {
        $this->getAndSetTest($value, 'cardType', 'card_type');
    }

    /**
     * @dataProvider validIssuerCountryDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetIssuerCountry($value): void
    {
        $this->getAndSetTest($value, 'issuerCountry', 'issuer_country');
    }

    /**
     * @dataProvider validIssuerNameDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetIssuerName($value): void
    {
        $this->getAndSetTest($value, 'issuerName', 'issuer_name');
    }

    /**
     * @dataProvider invalidFirstLastDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassFirst6
     */
    public function testSetInvalidFirst6(mixed $value, string $exceptionClassFirst6): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassFirst6);
        $instance->setFirst6($value);
    }

    /**
     * @dataProvider invalidFirstLastDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassFirst6
     */
    public function testSetterInvalidFirst6(mixed $value, string $exceptionClassFirst6): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassFirst6);
        $instance->first6 = $value;
    }

    /**
     * @dataProvider invalidFirstLastDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassLast4
     */
    public function testSetInvalidLast4(mixed $value, string $exceptionClassLast4): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassLast4);
        $instance->setLast4($value);
    }

    /**
     * @dataProvider invalidFirstLastDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassLast4
     */
    public function testSetterInvalidLast4(mixed $value, string $exceptionClassLast4): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassLast4);
        $instance->last4 = $value;
    }

    /**
     * @dataProvider invalidCardTypeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassCardType
     */
    public function testSetInvalidCardType(mixed $value, string $exceptionClassCardType): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassCardType);
        $instance->setCardType($value);
    }

    /**
     * @dataProvider invalidCardTypeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassCardType
     */
    public function testSetterInvalidCardType(mixed $value, string $exceptionClassCardType): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassCardType);
        $instance->card_type = $value;
    }

    /**
     * @dataProvider invalidIssuerCountryDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassIssuerCountry
     */
    public function testSetInvalidIssuerCountry(mixed $value, string $exceptionClassIssuerCountry): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassIssuerCountry);
        $instance->setIssuerCountry($value);
    }

    /**
     * @dataProvider invalidIssuerCountryDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassIssuerCountry
     */
    public function testSetterInvalidIssuerCountry(mixed $value, string $exceptionClassIssuerCountry): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassIssuerCountry);
        $instance->issuer_country = $value;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validLast4DataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(4, 4, '0123456789')];
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validFirst6DataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(6, 6, '0123456789')];
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validCardTypeDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::value(BankCardType::getValidValues())];
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function validIssuerCountryDataProvider(): array
    {
        return [
            ['RU'],
            ['EN'],
            ['UK'],
            ['AU'],
            [null],
            [''],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validIssuerNameDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(3, 35)];
        }
        $result[] = [''];
        $result[] = [null];

        return $result;
    }

    /**
     * @return array
     */
    public static function invalidCardTypeDataProvider(): array
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
     * @return array
     * @throws Exception
     */
    public static function invalidIssuerCountryDataProvider(): array
    {
        return [
            [Random::str(PayoutDestinationBankCardCard::ISO_3166_CODE_LENGTH + 1), InvalidPropertyValueException::class],
            [fopen(__FILE__, 'r'), TypeError::class],
            [Random::float(), InvalidPropertyValueException::class],
            [Random::int(), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidFirstLastDataProvider(): array
    {
        return [
            [Random::str(Random::int(1, 100)), InvalidPropertyValueException::class],
            [fopen(__FILE__, 'r'), TypeError::class],
            [Random::float(), InvalidPropertyValueException::class],
            [Random::int(), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    /**
     * @return PayoutDestinationBankCardCard
     */
    protected function getTestInstance(): PayoutDestinationBankCardCard
    {
        return new PayoutDestinationBankCardCard();
    }

    /**
     * @param $value
     * @param $property
     * @param $snakeCase
     * @return void
     */
    protected function getAndSetTest($value, $property, $snakeCase = null): void
    {
        $getter = 'get' . ucfirst($property);
        $setter = 'set' . ucfirst($property);

        $instance = $this->getTestInstance();

        self::assertNull($instance->{$getter}());
        self::assertNull($instance->{$property});
        if (null !== $snakeCase) {
            self::assertNull($instance->{$snakeCase});
        }

        $instance->{$setter}($value);

        self::assertEquals($value, $instance->{$getter}());
        self::assertEquals($value, $instance->{$property});
        if (null !== $snakeCase) {
            self::assertEquals($value, $instance->{$snakeCase});
        }

        $instance = $this->getTestInstance();

        $instance->{$property} = $value;

        self::assertEquals($value, $instance->{$getter}());
        self::assertEquals($value, $instance->{$property});
        if (null !== $snakeCase) {
            self::assertEquals($value, $instance->{$snakeCase});
        }

        if (null !== $snakeCase) {
            $instance = $this->getTestInstance();

            $instance->{$snakeCase} = $value;

            self::assertEquals($value, $instance->{$getter}());
            self::assertEquals($value, $instance->{$property});
            self::assertEquals($value, $instance->{$snakeCase});
        }
    }
}
