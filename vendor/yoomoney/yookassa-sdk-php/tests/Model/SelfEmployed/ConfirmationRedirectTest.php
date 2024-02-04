<?php

namespace Tests\YooKassa\Model\SelfEmployed;

use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationRedirect;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;

/**
 * @internal
 */
class ConfirmationRedirectTest extends AbstractTestConfirmation
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

    public static function validUrlDataProvider()
    {
        return [
            ['https://test.ru'],
            ['https://' . Random::str(1, 10, 'abcdefghijklmnopqrstuvwxyz') . '.ru',],
        ];
    }

    public static function invalidUrlDataProvider()
    {
        return [
            [true],
            [false],
            [[]],
            [new stdClass()],
        ];
    }

    protected function getTestInstance(): SelfEmployedConfirmationRedirect
    {
        return new SelfEmployedConfirmationRedirect();
    }

    protected function getExpectedType(): string
    {
        return SelfEmployedConfirmationType::REDIRECT;
    }
}
