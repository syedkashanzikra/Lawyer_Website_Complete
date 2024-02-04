<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank;

use Exception;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\PayerBankDetails;

/**
 * @internal
 */
class PayerBankDetailsTest extends TestCase
{
    /**
     * @dataProvider validStringDataProvider
     */
    public function testGetSetFullName(string $value): void
    {
        $this->getAndSetTest($value, 'fullName');
    }

    /**
     * @dataProvider validStringDataProvider
     */
    public function testGetSetShortName(string $value): void
    {
        $this->getAndSetTest($value, 'shortName');
    }

    /**
     * @dataProvider validStringDataProvider
     */
    public function testGetSetAddress(string $value): void
    {
        $this->getAndSetTest($value, 'address');
    }

    /**
     * @dataProvider validInnDataProvider
     */
    public function testGetSetInn(string $value): void
    {
        $this->getAndSetTest($value, 'inn');
    }

    /**
     * @dataProvider validKppDataProvider
     */
    public function testGetSetKpp(string $value): void
    {
        $this->getAndSetTest($value, 'kpp');
    }

    /**
     * @dataProvider validStringDataProvider
     */
    public function testGetSetBankName(string $value): void
    {
        $this->getAndSetTest($value, 'bankName');
    }

    /**
     * @dataProvider validStringDataProvider
     */
    public function testGetSetBankBranch(string $value): void
    {
        $this->getAndSetTest($value, 'bankBranch');
    }

    /**
     * @dataProvider validBikDataProvider
     */
    public function testGetSetBankBik(string $value): void
    {
        $this->getAndSetTest($value, 'bankBik');
    }

    /**
     * @dataProvider validAccountDataProvider
     */
    public function testGetSetAccount(string $value): void
    {
        $this->getAndSetTest($value, 'account');
    }

    /**
     * @throws Exception
     */
    public static function validStringDataProvider(): array
    {
        return [[Random::str(10)]];
    }

    /**
     * @throws Exception
     */
    public static function validAccountDataProvider(): array
    {
        return [[Random::str(20, 20, '0123456789')]];
    }

    /**
     * @throws Exception
     */
    public static function validBikDataProvider(): array
    {
        return [[Random::str(9, 9, '0123456789')]];
    }

    /**
     * @throws Exception
     */
    public static function validInnDataProvider(): array
    {
        return [
            [Random::str(10, 10, '0123456789')],
            [Random::str(12, 12, '0123456789')],
        ];
    }

    /**
     * @throws Exception
     */
    public static function validKppDataProvider(): array
    {
        return [[Random::str(9, 9, '0123456789')]];
    }


    protected function getTestInstance(): PayerBankDetails
    {
        return new PayerBankDetails();
    }

    /**
     * @param null $snakeCase
     * @param mixed $value
     */
    protected function getAndSetTest($value, string $property, $snakeCase = null): void
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
