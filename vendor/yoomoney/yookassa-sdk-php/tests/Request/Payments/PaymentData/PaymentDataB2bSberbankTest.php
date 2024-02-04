<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use Exception;
use InvalidArgumentException;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatDataRate;
use YooKassa\Model\Payment\PaymentMethod\B2b\Sberbank\VatDataType;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\PaymentDataB2bSberbank;

/**
 * @internal
 */
class PaymentDataB2bSberbankTest extends AbstractTestPaymentData
{
    /**
     * @dataProvider validPaymentPurposeDataProvider
     */
    public function testGetSetPaymentPurpose(string $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setPaymentPurpose($value);

        self::assertEquals($value, $instance->getPaymentPurpose());
        self::assertEquals($value, $instance->paymentPurpose);
    }

    /**
     * @dataProvider invalidPaymentPurposeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentPurpose($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setPaymentPurpose($value);
    }

    /**
     * @throws Exception
     */
    public static function validPaymentPurposeDataProvider(): array
    {
        return [
            [Random::str(16)],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidPaymentPurposeDataProvider(): array
    {
        return [
            [''],
            [Random::str(211)],
        ];
    }

    /**
     * @dataProvider validVatDataDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetVatData($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setVatData($value);

        if (is_array($value)) {
            self::assertEquals($value['type'], $instance->getVatData()->getType());
            self::assertEquals($value['type'], $instance->vatData->getType());
            if (isset($value['rate'])) {
                self::assertEquals($value['rate'], $instance->getVatData()->getRate());
                self::assertEquals($value['rate'], $instance->vatData->getRate());
            }
            if (isset($value['amount'])) {
                if (is_array($value['amount'])) {
                    self::assertEquals(
                        $value['amount']['value'],
                        (int) $instance->getVatData()->getAmount()->getValue()
                    );
                    self::assertEquals($value['amount']['currency'], $instance->vatData->getAmount()->getCurrency());
                } else {
                    self::assertEquals($value['amount'], $instance->getVatData()->getAmount());
                    self::assertEquals($value['amount'], $instance->vatData->getAmount());
                }
            }
        } else {
            self::assertEquals($value, $instance->getVatData());
            self::assertEquals($value, $instance->vatData);
        }
    }

    /**
     * @dataProvider invalidVatDataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidVatData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setVatData($value);
    }

    /**
     * @throws Exception
     */
    public static function validVatDataDataProvider(): array
    {
        return [
            [['type' => VatDataType::UNTAXED]],
            [[
                'type' => VatDataType::CALCULATED,
                'rate' => VatDataRate::RATE_10,
                'amount' => new MonetaryAmount(Random::int(1, 10000), CurrencyCode::EUR),
            ]],
            [
                [
                    'type' => VatDataType::UNTAXED,
                ],
            ],
            [
                [
                    'type' => VatDataType::CALCULATED,
                    'rate' => VatDataRate::RATE_10,
                    'amount' => new MonetaryAmount(Random::int(1, 10000), CurrencyCode::EUR),
                ],
            ],
            [
                [
                    'type' => VatDataType::MIXED,
                    'amount' => new MonetaryAmount(Random::int(1, 10000), CurrencyCode::EUR),
                ],
            ],
            [
                [
                    'type' => VatDataType::CALCULATED,
                    'rate' => VatDataRate::RATE_20,
                    'amount' => [
                        'value' => Random::int(1, 10000),
                        'currency' => CurrencyCode::USD,
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidVatDataDataProvider(): array
    {
        return [
            [0],
            [1],
            [-1],
            [''],
            [true],
            [new stdClass()],
            [
                [
                    'type' => VatDataType::CALCULATED,
                    'rate' => VatDataRate::RATE_10,
                    'amount' => Random::str(10),
                ],
            ],
        ];
    }

    protected function getTestInstance(): PaymentDataB2bSberbank
    {
        return new PaymentDataB2bSberbank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::B2B_SBERBANK;
    }
}
