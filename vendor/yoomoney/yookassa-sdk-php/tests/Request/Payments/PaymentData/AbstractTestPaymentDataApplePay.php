<?php

namespace Tests\YooKassa\Request\Payments\PaymentData;

use YooKassa\Helpers\Random;
use YooKassa\Request\Payments\PaymentData\PaymentDataApplePay;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;

abstract class AbstractTestPaymentDataApplePay extends AbstractTestPaymentData
{
    /**
     * @dataProvider validPaymentDataDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetPaymentData($value): void
    {
        /** @var PaymentDataApplePay $instance */
        $instance = $this->getTestInstance();

        $instance->setPaymentData($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentData());
            self::assertNull($instance->paymentData);
            self::assertNull($instance->payment_data);
        } else {
            self::assertEquals($value, $instance->getPaymentData());
            self::assertEquals($value, $instance->paymentData);
            self::assertEquals($value, $instance->payment_data);
        }

        $instance = $this->getTestInstance();
        $instance->paymentData = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentData());
            self::assertNull($instance->paymentData);
            self::assertNull($instance->payment_data);
        } else {
            self::assertEquals($value, $instance->getPaymentData());
            self::assertEquals($value, $instance->paymentData);
            self::assertEquals($value, $instance->payment_data);
        }

        $instance = $this->getTestInstance();
        $instance->payment_data = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentData());
            self::assertNull($instance->paymentData);
            self::assertNull($instance->payment_data);
        } else {
            self::assertEquals($value, $instance->getPaymentData());
            self::assertEquals($value, $instance->paymentData);
            self::assertEquals($value, $instance->payment_data);
        }
    }

    /**
     * @dataProvider invalidPaymentDataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentData($value): void
    {
        $this->expectException(EmptyPropertyValueException::class);

        /** @var PaymentDataApplePay $instance */
        $instance = $this->getTestInstance();
        $instance->setPaymentData($value);
    }

    /**
     * @dataProvider invalidPaymentDataDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPaymentData($value): void
    {
        $this->expectException(EmptyPropertyValueException::class);

        /** @var PaymentDataApplePay $instance */
        $instance = $this->getTestInstance();
        $instance->paymentData = $value;
    }

    /**
     * @dataProvider invalidPaymentDataDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPayment_data($value): void
    {
        $this->expectException(EmptyPropertyValueException::class);

        /** @var PaymentDataApplePay $instance */
        $instance = $this->getTestInstance();
        $instance->payment_data = $value;
    }

    public function validPaymentDataDataProvider()
    {
        return [
            ['https://test.ru'],
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
