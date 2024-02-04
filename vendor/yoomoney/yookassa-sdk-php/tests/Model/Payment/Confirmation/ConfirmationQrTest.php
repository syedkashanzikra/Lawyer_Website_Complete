<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use YooKassa\Model\Payment\Confirmation\ConfirmationQr;
use YooKassa\Model\Payment\ConfirmationType;

/**
 * @internal
 */
class ConfirmationQrTest extends AbstractTestConfirmation
{
    /**
     * @dataProvider validUrlDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetConfirmationData($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setConfirmationData($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationData());
            self::assertNull($instance->confirmationData);
            self::assertNull($instance->confirmation_data);
        } else {
            self::assertEquals($value, $instance->getConfirmationData());
            self::assertEquals($value, $instance->confirmationData);
            self::assertEquals($value, $instance->confirmation_data);
        }

        $instance->confirmationData = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationData());
            self::assertNull($instance->confirmationData);
            self::assertNull($instance->confirmation_data);
        } else {
            self::assertEquals($value, $instance->getConfirmationData());
            self::assertEquals($value, $instance->confirmationData);
            self::assertEquals($value, $instance->confirmation_data);
        }

        $instance->confirmation_data = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationData());
            self::assertNull($instance->confirmationData);
            self::assertNull($instance->confirmation_data);
        } else {
            self::assertEquals($value, $instance->getConfirmationData());
            self::assertEquals($value, $instance->confirmationData);
            self::assertEquals($value, $instance->confirmation_data);
        }
    }

    public function validEnforceDataProvider()
    {
        return [
            [true],
            [false],
            [null],
            [''],
            [0],
            [1],
            [100],
        ];
    }

    public static function validUrlDataProvider()
    {
        return [
            ['wechat://pay/testurl?pr=xXxXxX'],
            ['https://test.ru'],
        ];
    }

    protected function getTestInstance(): ConfirmationQr
    {
        return new ConfirmationQr();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::QR;
    }
}
