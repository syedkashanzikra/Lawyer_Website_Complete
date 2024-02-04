<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use YooKassa\Model\Payment\Confirmation\ConfirmationRedirect;
use YooKassa\Model\Payment\ConfirmationType;

/**
 * @internal
 */
class ConfirmationRedirectTest extends AbstractTestConfirmation
{
    /**
     * @dataProvider validEnforceDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetEnforce($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setEnforce($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getEnforce());
            self::assertNull($instance->enforce);
        } else {
            self::assertEquals((bool) $value, $instance->getEnforce());
            self::assertEquals((bool) $value, $instance->enforce);
        }

        $instance = $this->getTestInstance();
        $instance->enforce = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getEnforce());
            self::assertNull($instance->enforce);
        } else {
            self::assertEquals((bool) $value, $instance->getEnforce());
            self::assertEquals((bool) $value, $instance->enforce);
        }
    }

    /**
     * @dataProvider validUrlDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetReturnUrl($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setReturnUrl($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getReturnUrl());
            self::assertNull($instance->returnUrl);
            self::assertNull($instance->return_url);
        } else {
            self::assertEquals($value, $instance->getReturnUrl());
            self::assertEquals($value, $instance->returnUrl);
            self::assertEquals($value, $instance->return_url);
        }

        $instance->setReturnUrl(null);
        self::assertNull($instance->getReturnUrl());
        self::assertNull($instance->returnUrl);
        self::assertNull($instance->return_url);

        $instance->returnUrl = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getReturnUrl());
            self::assertNull($instance->returnUrl);
            self::assertNull($instance->return_url);
        } else {
            self::assertEquals($value, $instance->getReturnUrl());
            self::assertEquals($value, $instance->returnUrl);
            self::assertEquals($value, $instance->return_url);
        }

        $instance->return_url = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getReturnUrl());
            self::assertNull($instance->returnUrl);
            self::assertNull($instance->return_url);
        } else {
            self::assertEquals($value, $instance->getReturnUrl());
            self::assertEquals($value, $instance->returnUrl);
            self::assertEquals($value, $instance->return_url);
        }
    }

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

    public static function validEnforceDataProvider()
    {
        return [
            [true],
            [false],
            [0],
            [1],
            [100],
        ];
    }

    public static function validUrlDataProvider()
    {
        return [
            ['https://test.ru'],
            ['https://shop.store'],
        ];
    }

    protected function getTestInstance(): ConfirmationRedirect
    {
        return new ConfirmationRedirect();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::REDIRECT;
    }
}
