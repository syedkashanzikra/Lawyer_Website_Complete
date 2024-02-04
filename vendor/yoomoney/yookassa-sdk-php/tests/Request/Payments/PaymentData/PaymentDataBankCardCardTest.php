<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\PaymentData\PaymentDataBankCardCard;

/**
 * @internal
 */
class PaymentDataBankCardCardTest extends TestCase
{
    /**
     * @dataProvider validNumberDataProvider
     */
    public function testGetSetNumber(string $value): void
    {
        $this->getAndSetTest($value, 'number');
    }

    /**
     * @dataProvider invalidNumberDataProvider
     */
    public function testSetInvalidNumber(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setNumber($value);
    }

    /**
     * @dataProvider invalidNumberDataProvider
     */
    public function testSetterInvalidNumber(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->number = $value;
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
     * @dataProvider validCscDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCsc($value): void
    {
        $this->getAndSetTest($value, 'csc');
    }

    /**
     * @dataProvider invalidCscDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCsc($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCsc($value);
    }

    /**
     * @dataProvider invalidCscDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCsc($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->csc = $value;
    }

    /**
     * @dataProvider validCardholderDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCardholder($value): void
    {
        $this->getAndSetTest($value, 'cardholder');
    }

    /**
     * @dataProvider invalidCardholderDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCardholder($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCardholder($value);
    }

    /**
     * @dataProvider invalidCardholderDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCardholder($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->cardholder = $value;
    }

    public static function validNumberDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 3; $i++) {
            $result[] = [Random::str(16, '0123456789')];
            $result[] = [Random::str(17, '0123456789')];
            $result[] = [Random::str(18, '0123456789')];
            $result[] = [Random::str(19, '0123456789')];
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

    public static function validCscDataProvider()
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(3, 4, '0123456789')];
        }

        return $result;
    }

    public static function validCardholderDataProvider()
    {
        $values = 'abcdefghijklmnopqrstuvwxyz';
        $values .= strtoupper($values) . ' ';
        $result = [
            [Random::str(1, $values)],
            [Random::str(26, $values)],
        ];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [Random::str(1, 26, $values)];
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public static function invalidNumberDataProvider(): array
    {
        return [
            [''],
            [null],
            [0],
            [1],
            [-1],
            [Random::str(15, '0123456789')],
            [Random::str(20, '0123456789')],
        ];
    }

    public static function invalidYearDataProvider()
    {
        return [
            [''],
            [null],
            [0],
            [1],
            [-1],
            ['5'],
            [Random::str(1, 1, '0123456789')],
            [Random::str(2, 2, '0123456789')],
            [Random::str(5, 5, '0123456789')],
            [Random::str(6, 6, '0123456789')],
        ];
    }

    public static function invalidMonthDataProvider()
    {
        return [
            [''],
            [null],
            [0],
            [1],
            [-1],
            ['5'],
            [Random::str(1, 1, '0123456789')],
            [Random::str(3, 3, '0123456789')],
            ['13'],
            ['16'],
        ];
    }

    public static function invalidCscDataProvider()
    {
        return [
            [1],
            [-1],
            ['5'],
            [Random::str(2, 2, '0123456789')],
            [Random::str(5, 5, '0123456789')],
            [Random::str(6, 6, '0123456789')],
        ];
    }

    public static function invalidCardholderDataProvider()
    {
        $values = 'abcdefghijklmnopqrstuvwxyz';
        $values .= strtoupper($values) . ' ';

        return [
            [1],
            [-1],
            ['5'],
            [Random::str(2, 2, '0123456789')],
            [Random::str(5, 5, '0123456789')],
            [Random::str(6, 6, '0123456789')],
            [Random::str(27, 27, $values)],
        ];
    }

    protected function getTestInstance()
    {
        return new PaymentDataBankCardCard();
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
