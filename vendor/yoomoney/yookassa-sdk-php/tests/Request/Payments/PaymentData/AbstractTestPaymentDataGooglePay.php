<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use InvalidArgumentException;
use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\PaymentData\PaymentDataGooglePay;

abstract class AbstractTestPaymentDataGooglePay extends AbstractTestPaymentData
{
    /**
     * @dataProvider validPaymentDataDataProvider
     */
    public function testGetSetPaymentMethodToken(string $data): void
    {
        /** @var PaymentDataGooglePay $instance */
        $instance = $this->getTestInstance();

        $instance->setPaymentMethodToken($data);
        self::assertEquals($data, $instance->getPaymentMethodToken());

        $instance = $this->getTestInstance();
        $instance->paymentMethodToken = $data;
        self::assertEquals($data, $instance->getPaymentMethodToken());
    }

    /**
     * @dataProvider validPaymentDataDataProvider
     */
    public function testGetSetGoogleTransactionId(string $data): void
    {
        /** @var PaymentDataGooglePay $instance */
        $instance = $this->getTestInstance();
        $instance->setGoogleTransactionId($data);
        self::assertEquals($data, $instance->getGoogleTransactionId());

        $instance = $this->getTestInstance();
        $instance->googleTransactionId = $data;
        self::assertEquals($data, $instance->getGoogleTransactionId());
    }

    /**
     * @dataProvider invalidPaymentDataDataProvider
     *
     * @param mixed $data
     */
    public function testSetPaymentMethodToken($data): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var PaymentDataGooglePay $instance */
        $instance = $this->getTestInstance();
        $instance->setPaymentMethodToken($data);
    }

    /**
     * @dataProvider invalidPaymentDataDataProvider
     *
     * @param mixed $data
     */
    public function testSetGoogleTransactionId($data): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var PaymentDataGooglePay $instance */
        $instance = $this->getTestInstance();
        $instance->setGoogleTransactionId($data);
    }

    public function validPaymentDataDataProvider()
    {
        return [
            [Random::str(256)],
            [Random::str(1024)],
        ];
    }

    public function invalidPaymentDataDataProvider()
    {
        return [
            [null],
            [''],
            [false],
        ];
    }
}
