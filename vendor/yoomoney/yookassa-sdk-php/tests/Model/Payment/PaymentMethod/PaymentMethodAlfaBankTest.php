<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodAlfaBank;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodAlfaBankTest extends AbstractTestPaymentMethod
{
    /**
     * @dataProvider validLoginDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetLogin($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setLogin($value);
        self::assertEquals($value, $instance->getLogin());
        self::assertEquals($value, $instance->login);

        $instance = $this->getTestInstance();
        $instance->login = $value;
        self::assertEquals($value, $instance->getLogin());
        self::assertEquals($value, $instance->login);
    }

    public static function validLoginDataProvider()
    {
        return [
            [null],
            [''],
            ['123'],
            [Random::str(256)],
            [Random::str(1024)],
        ];
    }

    protected function getTestInstance(): PaymentMethodAlfaBank
    {
        return new PaymentMethodAlfaBank();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::ALFABANK;
    }
}
