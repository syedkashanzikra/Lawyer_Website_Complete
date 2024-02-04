<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\BankCardSource;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodBankCard;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodBankCardTest extends AbstractTestPaymentMethod
{
    /**
     * @dataProvider validCardProvider
     */
    public function testGetSetCard(mixed $value): void
    {
        $this->getAndSetTest($value, 'card');
    }

    /**
     * @dataProvider invalidCardDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCard($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCard($value);
    }

    public function validCardProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [
                [
                    'type' => PaymentMethodType::BANK_CARD,
                    'card' => [
                        'first6' => Random::str(6, '0123456789'),
                        'last4' => Random::str(4, '0123456789'),
                        'expiry_year' => Random::int(2000, 2200),
                        'expiry_month' => Random::value($this->validExpiryMonth()),
                        'card_type' => Random::value(BankCardType::getValidValues()),
                        'issuer_country' => Random::value($this->validIssuerCountry()),
                        'issuer_name' => Random::str(3, 35),
                        'source' => Random::value(BankCardSource::getValidValues()),
                    ],
                ],
            ];
        }

        return $result;
    }

    public static function invalidCardDataProvider()
    {
        return [
            ['null'],
            [0],
            [1],
            [-1],
            [new stdClass()],
            [Random::str(3, '0123456789')],
            [Random::str(5, '0123456789')],
        ];
    }

    protected function getTestInstance(): PaymentMethodBankCard
    {
        return new PaymentMethodBankCard();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::BANK_CARD;
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

        $instance->{$setter}($value[$property]);

        self::assertEquals($value[$property], $instance->{$getter}()->toArray());
        self::assertEquals($value[$property], $instance->{$property}->toArray());
        if (null !== $snakeCase) {
            self::assertEquals($value[$property], $instance->{$snakeCase}->toArray());
        }

        $instance = $this->getTestInstance();

        $instance->{$property} = $value[$property];

        self::assertEquals($value[$property], $instance->{$getter}()->toArray());
        self::assertEquals($value[$property], $instance->{$property}->toArray());
        if (null !== $snakeCase) {
            self::assertEquals($value[$property], $instance->{$snakeCase}->toArray());
        }

        if (null !== $snakeCase) {
            $instance = $this->getTestInstance();

            $instance->{$snakeCase} = $value[$property];

            self::assertEquals($value[$property], $instance->{$getter}()->toArray());
            self::assertEquals($value[$property], $instance->{$property}->toArray());
            self::assertEquals($value[$property], $instance->{$snakeCase}->toArray());
        }
    }

    protected function getOnlyTest($instance, $value, $property, $snakeCase = null): void
    {
        $getter = 'get' . ucfirst($property);

        if (null !== $snakeCase) {
            self::assertEquals($value[$snakeCase], $instance->{$getter}());
            self::assertEquals($value[$snakeCase], $instance->{$property});
            self::assertEquals($value[$snakeCase], $instance->{$snakeCase});
        } else {
            self::assertEquals($value[$property], $instance->{$getter}());
            self::assertEquals($value[$property], $instance->{$property});
        }
    }

    private function validExpiryMonth(): array
    {
        return [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        ];
    }

    private function validIssuerCountry()
    {
        return [
            'RU',
            'EN',
            'UK',
            'AU',
        ];
    }
}
