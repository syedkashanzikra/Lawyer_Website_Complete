<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use YooKassa\Model\Payment\Confirmation\ConfirmationEmbedded;
use YooKassa\Model\Payment\ConfirmationType;

/**
 * @internal
 */
class ConfirmationEmbeddedTest extends AbstractTestConfirmation
{
    /**
     * @dataProvider validConfirmationTokenDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetConfirmationToken($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setConfirmationToken($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationToken());
            self::assertNull($instance->confirmationToken);
        } else {
            self::assertEquals($value, $instance->getConfirmationToken());
            self::assertEquals($value, $instance->confirmationToken);
        }

        $instance = $this->getTestInstance();
        $instance->confirmationToken = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getConfirmationToken());
            self::assertNull($instance->confirmationToken);
        } else {
            self::assertEquals($value, $instance->getConfirmationToken());
            self::assertEquals($value, $instance->confirmationToken);
        }
    }

    public static function validConfirmationTokenDataProvider(): array
    {
        return [
            ['ct-2454fc2d-000f-5000-9000-12a816bfbb35'],
        ];
    }

    protected function getTestInstance(): ConfirmationEmbedded
    {
        return new ConfirmationEmbedded();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::EMBEDDED;
    }
}
