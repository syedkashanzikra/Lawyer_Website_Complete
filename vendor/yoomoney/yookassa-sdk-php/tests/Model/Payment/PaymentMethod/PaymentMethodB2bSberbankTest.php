<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\CalculatedVatData;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\PayerBankDetails;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatData;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatDataRate;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatDataType;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodB2bSberbank;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodB2bSberbankTest extends TestCase
{
    /**
     * @dataProvider validPaymentPurposeDataProvider
     */
    public function testSetGetPaymentPurpose(string $value): void
    {
        $instance = $this->getTestInstance();
        $instance->setPaymentPurpose($value);
        self::assertNotNull($instance->getPaymentPurpose());
        self::assertEquals($value, $instance->getPaymentPurpose());
    }

    /**
     * @dataProvider validVatDataProvider
     */
    public function testSetGetValidVatData(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->setVatData($value);
        self::assertNotNull($instance->getVatData());
        self::assertInstanceOf(VatData::class, $instance->getVatData());
    }

    /**
     * @dataProvider invalidVatDataProvider
     */
    public function testSetGetInvalidVatData(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setVatData($value);
    }

    /**
     * @dataProvider validPayerBankDetailsDataProvider
     */
    public function testSetGetValidPayerBankDetails(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->setPayerBankDetails($value);
        self::assertNotNull($instance->getPayerBankDetails());
        self::assertInstanceOf(PayerBankDetails::class, $instance->getPayerBankDetails());
    }

    /**
     * @dataProvider invalidPayerBankDetailsDataProvider
     */
    public function testSetGetInvalidPayerBankDetails(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setPayerBankDetails($value);
    }

    public static function validPaymentPurposeDataProvider(): array
    {
        return [
            [Random::str(16)],
        ];
    }

    public static function validVatDataProvider(): array
    {
        return [
            [
                new CalculatedVatData([
                    'type' => Random::value(VatDataType::getValidValues()),
                    'rate' => Random::value(VatDataRate::getValidValues()),
                    'amount' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::EUR]),
                ]),
            ],
            [
                [
                    'type' => VatDataType::CALCULATED,
                    'rate' => VatDataRate::RATE_20,
                    'amount' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::RUB])
                ],
            ],
            [
                [
                    'type' => VatDataType::UNTAXED,
                ],
            ],
            [
                [
                    'type' => VatDataType::MIXED,
                    'amount' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::RUB])
                ],
            ],
        ];
    }

    public static function invalidVatDataProvider(): array
    {
        return [
            [new stdClass()],
        ];
    }

    public static function validPayerBankDetailsDataProvider(): array
    {
        return [
            [
                [
                    'fullName' => Random::str(2, 256),
                    'shortName' => Random::str(2, 100),
                    'address' => Random::str(2, 100),
                    'inn' => Random::str(12, 12, '1234567890'),
                    'kpp' => Random::str(9, 9, '0123456789'),
                    'bankName' => Random::str(2, 100),
                    'bankBranch' => Random::str(2, 100),
                    'bankBik' => Random::str(9, 9, '0123456789'),
                    'account' => Random::str(20, 20, '0123456789'),
                ],
            ],
            [
                new PayerBankDetails([
                    'fullName' => Random::str(2, 256),
                    'shortName' => Random::str(2, 100),
                    'address' => Random::str(2, 100),
                    'inn' => Random::str(12, 12, '1234567890'),
                    'kpp' => Random::str(9, 9, '0123456789'),
                    'bankName' => Random::str(2, 100),
                    'bankBranch' => Random::str(2, 100),
                    'bankBik' => Random::str(9, 9, '0123456789'),
                    'account' => Random::str(20, 20, '0123456789'),
                ]),
            ],
        ];
    }

    public static function invalidPayerBankDetailsDataProvider(): array
    {
        return [
            [new stdClass()],
        ];
    }

    protected function getTestInstance(): PaymentMethodB2bSberbank
    {
        return new PaymentMethodB2bSberbank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::B2B_SBERBANK;
    }
}
