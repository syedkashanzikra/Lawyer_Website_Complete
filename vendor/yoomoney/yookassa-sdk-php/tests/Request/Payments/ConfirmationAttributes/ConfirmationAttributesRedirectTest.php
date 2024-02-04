<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesRedirect;

/**
 * @internal
 */
class ConfirmationAttributesRedirectTest extends AbstractTestConfirmationAttributes
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

    public static function validEnforceDataProvider()
    {
        return [
            [true],
            [false],
        ];
    }

    public static function validUrlDataProvider()
    {
        return [
            ['https://test.com'],
        ];
    }

    protected function getTestInstance(): ConfirmationAttributesRedirect
    {
        return new ConfirmationAttributesRedirect();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::REDIRECT;
    }
}
