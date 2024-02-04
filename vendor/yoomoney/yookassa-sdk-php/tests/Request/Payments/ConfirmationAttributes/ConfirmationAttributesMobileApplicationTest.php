<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use stdClass;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesMobileApplication;

/**
 * @internal
 */
class ConfirmationAttributesMobileApplicationTest extends AbstractTestConfirmationAttributes
{
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
            ['https://test.ru'],
        ];
    }

    protected function getTestInstance(): ConfirmationAttributesMobileApplication
    {
        return new ConfirmationAttributesMobileApplication();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::MOBILE_APPLICATION;
    }
}
