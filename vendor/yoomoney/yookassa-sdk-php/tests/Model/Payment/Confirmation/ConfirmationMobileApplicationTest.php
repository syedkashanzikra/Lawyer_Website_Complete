<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use stdClass;
use YooKassa\Model\Payment\Confirmation\ConfirmationMobileApplication;
use YooKassa\Model\Payment\ConfirmationType;

/**
 * @internal
 */
class ConfirmationMobileApplicationTest extends AbstractTestConfirmation
{
    /**
     * @dataProvider validUrlDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetConfirmationUrl($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setConfirmationUrl($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationUrl());
            self::assertNull($instance->confirmationUrl);
            self::assertNull($instance->confirmation_url);
        } else {
            self::assertEquals($value, $instance->getConfirmationUrl());
            self::assertEquals($value, $instance->confirmationUrl);
            self::assertEquals($value, $instance->confirmation_url);
        }

        $instance->confirmationUrl = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationUrl());
            self::assertNull($instance->confirmationUrl);
            self::assertNull($instance->confirmation_url);
        } else {
            self::assertEquals($value, $instance->getConfirmationUrl());
            self::assertEquals($value, $instance->confirmationUrl);
            self::assertEquals($value, $instance->confirmation_url);
        }

        $instance->confirmation_url = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationUrl());
            self::assertNull($instance->confirmationUrl);
            self::assertNull($instance->confirmation_url);
        } else {
            self::assertEquals($value, $instance->getConfirmationUrl());
            self::assertEquals($value, $instance->confirmationUrl);
            self::assertEquals($value, $instance->confirmation_url);
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

    public function invalidEnforceDataProvider()
    {
        return [
            ['true'],
            ['false'],
            [[]],
            [new stdClass()],
        ];
    }

    public static function validUrlDataProvider()
    {
        return [
            ['wechat://pay/testurl?pr=xXxXxX'],
            ['https://test.ru'],
        ];
    }

    protected function getTestInstance(): ConfirmationMobileApplication
    {
        return new ConfirmationMobileApplication();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::MOBILE_APPLICATION;
    }
}
