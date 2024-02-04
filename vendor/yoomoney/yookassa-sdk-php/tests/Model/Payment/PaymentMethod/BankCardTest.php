<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\BankCard;
use YooKassa\Model\Payment\PaymentMethod\BankCardSource;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;

/**
 * @internal
 */
class BankCardTest extends TestCase
{
    /**
     * @dataProvider validLast4DataProvider
     */
    public function testGetSetLast4(string $value): void
    {
        $this->getAndSetTest($value, 'last4');
    }

    /**
     * @dataProvider invalidLast4DataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLast4($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setLast4($value);
    }

    /**
     * @dataProvider invalidLast4DataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidLast4($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->last4 = $value;
    }

    /**
     * @dataProvider validFirst6DataProvider
     */
    public function testGetSetFirst6(string $value): void
    {
        $this->getAndSetTest($value, 'first6');
    }

    /**
     * @dataProvider invalidFirst6DataProvider
     *
     * @param mixed $value
     */
    public function testSetFirst6Invalid($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setFirst6($value);
    }

    /**
     * @dataProvider invalidFirst6DataProvider
     *
     * @param mixed $value
     */
    public function testSetterFirst6Invalid($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->first6 = $value;
    }

    /**
     * @dataProvider validExpiryYearDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetExpiryYear($value): void
    {
        $this->getAndSetTest($value, 'expiryYear', 'expiry_year');
    }

    /**
     * @dataProvider invalidYearDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidYear($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setExpiryYear($value);
    }

    /**
     * @dataProvider invalidYearDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidYear($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->expiryYear = $value;
    }

    /**
     * @dataProvider invalidYearDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeYear($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->expiry_year = $value;
    }

    /**
     * @dataProvider invalidMonthDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidMonth($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->expiryMonth = $value;
    }

    /**
     * @dataProvider invalidMonthDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeMonth($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->expiry_month = $value;
    }

    /**
     * @dataProvider validExpiryMonthDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetExpiryMonth($value): void
    {
        $this->getAndSetTest($value, 'expiryMonth', 'expiry_month');
    }

    /**
     * @dataProvider invalidMonthDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMonth($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setExpiryMonth($value);
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
     * @dataProvider validSourceDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetSource($value): void
    {
        $this->getAndSetTest($value, 'source', 'source');
    }

    /**
     * @dataProvider invalidCardTypeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCardType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCardType($value);
    }

    /**
     * @dataProvider invalidCardTypeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCardType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->cardType = $value;
    }

    /**
     * @dataProvider invalidCardTypeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeCardType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->card_type = $value;
    }

    /**
     * @dataProvider invalidIssuerCountryDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidIssuerCountry($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setIssuerCountry($value);
    }

    /**
     * @dataProvider invalidIssuerCountryDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidIssuerCountry($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->issuerCountry = $value;
    }

    /**
     * @dataProvider invalidIssuerCountryDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeIssuerCountry($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->issuer_country = $value;
    }

    /**
     * @dataProvider invalidSourceDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidSource($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setSource($value);
    }

    /**
     * @dataProvider invalidSourceDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSource($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->source = $value;
    }

    public static function validLast4DataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(4, '0123456789')];
        }

        return $result;
    }

    public static function validFirst6DataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(6, '0123456789')];
        }

        return $result;
    }

    public static function validExpiryYearDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::int(2000, 2200)];
        }

        return $result;
    }

    public static function validExpiryMonthDataProvider(): array
    {
        return [
            ['01'],
            ['02'],
            ['03'],
            ['04'],
            ['05'],
            ['06'],
            ['07'],
            ['08'],
            ['09'],
            ['10'],
            ['11'],
            ['12'],
        ];
    }

    public static function validCardTypeDataProvider()
    {
        $result = [];
        foreach (BankCardType::getValidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    public static function validIssuerCountryDataProvider()
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

    public static function validIssuerNameDataProvider()
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(3, 35)];
        }
        $result[] = [''];
        $result[] = [null];

        return $result;
    }

    public static function validSourceDataProvider()
    {
        $result = [];
        foreach (BankCardSource::getValidValues() as $value) {
            $result[] = [$value];
        }
        $result[] = [null];

        return $result;
    }

    public static function invalidLast4DataProvider()
    {
        return [
            [null],
            [0],
            [1],
            [-1],
            [Random::str(3, '0123456789')],
            [Random::str(5, '0123456789')],
        ];
    }

    public static function invalidFirst6DataProvider()
    {
        return [
            ['null'],
            [1],
            [-1],
            [Random::str(5, '0123456789')],
            [Random::str(7, '0123456789')],
        ];
    }

    public static function invalidYearDataProvider()
    {
        return [
            [null],
            [0],
            [1],
            [-1],
            ['5'],
            [Random::str(1, '0123456789')],
            [Random::str(2, '0123456789')],
            [Random::str(3, '0123456789')],
        ];
    }

    public static function invalidMonthDataProvider()
    {
        return [
            [null],
            [0],
            [-1],
            [Random::str(3, '0123456789')],
            ['13'],
            ['16'],
        ];
    }

    public static function invalidCardTypeDataProvider()
    {
        return [
            [''],
            [null],
            [false],
        ];
    }

    public static function invalidIssuerCountryDataProvider()
    {
        return [
            [Random::str(3, 4)],
        ];
    }

    public static function invalidSourceDataProvider()
    {
        return [
            [Random::str(3, 6)],
            [Random::int(1, 2)],
            [true],
        ];
    }

    protected function getTestInstance(): BankCard
    {
        return new BankCard();
    }

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
