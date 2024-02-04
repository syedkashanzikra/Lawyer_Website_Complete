<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Request\Payments\PaymentData\AbstractPaymentData;
use YooKassa\Request\Payments\PaymentData\PaymentDataBankCardCard;
use YooKassa\Request\Payments\PaymentData\PaymentDataFactory;

/**
 * @internal
 */
class PaymentDataFactoryTest extends TestCase
{
    /**
     * @dataProvider validTypeDataProvider
     */
    public function testFactory(string $type): void
    {
        $instance = $this->getTestInstance();
        $paymentData = $instance->factory($type);
        self::assertNotNull($paymentData);
        self::assertInstanceOf(AbstractPaymentData::class, $paymentData);
        self::assertEquals($type, $paymentData->getType());
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $type
     */
    public function testInvalidFactory($type): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->factory($type);
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testFactoryFromArray(array $options): void
    {
        $instance = $this->getTestInstance();
        $paymentData = $instance->factoryFromArray($options);
        self::assertNotNull($paymentData);
        self::assertInstanceOf(AbstractPaymentData::class, $paymentData);

        foreach ($options as $property => $value) {
            self::assertEquals($paymentData->{$property}, $value);
        }

        $type = $options['type'];
        unset($options['type']);
        $paymentData = $instance->factoryFromArray($options, $type);
        self::assertNotNull($paymentData);
        self::assertInstanceOf(AbstractPaymentData::class, $paymentData);

        self::assertEquals($type, $paymentData->getType());
        foreach ($options as $property => $value) {
            self::assertEquals($paymentData->{$property}, $value);
        }
    }

    /**
     * @dataProvider invalidDataArrayDataProvider
     *
     * @param mixed $options
     */
    public function testInvalidFactoryFromArray($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->factoryFromArray($options);
    }

    public static function validTypeDataProvider()
    {
        $result = [];
        foreach (PaymentMethodType::getEnabledValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    public static function invalidTypeDataProvider()
    {
        return [
            [''],
            [0],
            [1],
            [-1],
            ['5'],
            [Random::str(10)],
        ];
    }

    public static function validArrayDataProvider()
    {
        $result = [
            [
                [
                    'type' => PaymentMethodType::GOOGLE_PAY,
                    'paymentMethodToken' => Random::str(10, 20),
                    'googleTransactionId' => Random::str(10, 20),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::APPLE_PAY,
                    'paymentData' => Random::str(10, 20),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::BANK_CARD,
                    'card' => new PaymentDataBankCardCard([
                        'number' => Random::str(16, 19, '0123456789'),
                        'expiry_year' => (string) Random::int(2023, 2025),
                        'expiry_month' => str_pad((string) Random::int(1, 12), 2, '0', STR_PAD_LEFT),
                        'csc' => Random::str(3, 4, '0123456789'),
                        'cardholder' => Random::str(10, 20, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ '),
                    ]),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::CASH,
                    'phone' => Random::str(4, 15, '0123456789'),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::MOBILE_BALANCE,
                    'phone' => Random::str(4, 15, '0123456789'),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::SBERBANK,
                    'phone' => Random::str(4, 15, '0123456789'),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::YOO_MONEY,
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::INSTALLMENTS,
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::TINKOFF_BANK,
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::SBP,
                ],
            ],
        ];
        foreach (PaymentMethodType::getEnabledValues() as $value) {
            $result[] = [['type' => $value]];
        }

        return $result;
    }

    public static function invalidDataArrayDataProvider()
    {
        return [
            [[]],
            [['type' => 'test']],
            [
                [
                    'type' => PaymentMethodType::ALFABANK,
                    'login' => Random::str(10, 20),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::UNKNOWN,
                ],
            ],
        ];
    }

    protected function getTestInstance()
    {
        return new PaymentDataFactory();
    }
}
