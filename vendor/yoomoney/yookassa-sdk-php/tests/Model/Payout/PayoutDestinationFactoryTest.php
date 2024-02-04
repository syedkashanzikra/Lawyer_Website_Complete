<?php

namespace Tests\YooKassa\Model\Payout;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\AbstractPayoutDestination;
use YooKassa\Model\Payout\PayoutDestinationFactory;
use YooKassa\Model\Payout\PayoutDestinationType;

/**
 * @internal
 */
class PayoutDestinationFactoryTest extends TestCase
{
    /**
     * @dataProvider validTypeDataProvider
     */
    public function testFactory(string $type): void
    {
        $instance = $this->getTestInstance();
        $paymentData = $instance->factory($type);
        self::assertNotNull($paymentData);
        self::assertInstanceOf(AbstractPayoutDestination::class, $paymentData);
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
        self::assertInstanceOf(AbstractPayoutDestination::class, $paymentData);

        foreach ($options as $property => $value) {
            if (!is_array($value)) {
                self::assertEquals($paymentData->{$property}, $value);
            } else {
                self::assertEquals($paymentData->{$property}->toArray(), $value);
            }
        }

        $type = $options['type'];
        unset($options['type']);
        $paymentData = $instance->factoryFromArray($options, $type);
        self::assertNotNull($paymentData);
        self::assertInstanceOf(AbstractPayoutDestination::class, $paymentData);

        self::assertEquals($type, $paymentData->getType());
        foreach ($options as $property => $value) {
            if (!is_array($value)) {
                self::assertEquals($paymentData->{$property}, $value);
            } else {
                self::assertEquals($paymentData->{$property}->toArray(), $value);
            }
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

    /**
     * @return array
     */
    public static function validTypeDataProvider(): array
    {
        $result = [];
        foreach (PayoutDestinationType::getEnabledValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidTypeDataProvider(): array
    {
        return [
            [''],
            [null],
            [0],
            [1],
            [-1],
            ['5'],
            [Random::str(10)],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validArrayDataProvider(): array
    {
        $result = [
            [
                [
                    'type' => PaymentMethodType::BANK_CARD,
                    'card' => [
                        'first6' => Random::str(6, '0123456789'),
                        'last4' => Random::str(4, '0123456789'),
                        'card_type' => Random::value(BankCardType::getValidValues()),
                        'issuer_country' => Random::value(self::validIssuerCountry()),
                        'issuer_name' => Random::str(4, 50),
                    ],
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::YOO_MONEY,
                    'account_number' => Random::str(11, 33, '1234567890'),
                ],
            ],
            [
                [
                    'type' => PaymentMethodType::SBP,
                    'phone' => Random::str(4, 15, '1234567890'),
                    'bank_id' => Random::str(4, 12),
                ],
            ],
        ];
        foreach (PayoutDestinationType::getEnabledValues() as $value) {
            $result[] = [['type' => $value]];
        }

        return $result;
    }

    private static function validIssuerCountry(): array
    {
        return [
            'RU',
            'EN',
            'UK',
            'AU',
        ];
    }

    /**
     * @return array
     */
    public static function invalidDataArrayDataProvider(): array
    {
        return [
            [[]],
            [['type' => 'test']],
        ];
    }

    /**
     * @return PayoutDestinationFactory
     */
    protected function getTestInstance(): PayoutDestinationFactory
    {
        return new PayoutDestinationFactory();
    }
}
