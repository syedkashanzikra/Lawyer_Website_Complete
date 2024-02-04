<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use YooKassa\Model\Payment\Confirmation\ConfirmationCodeVerification;
use YooKassa\Model\Payment\ConfirmationType;

/**
 * @internal
 */
class ConfirmationCodeVerificationTest extends AbstractTestConfirmation
{
    protected function getTestInstance(): ConfirmationCodeVerification
    {
        return new ConfirmationCodeVerification();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::CODE_VERIFICATION;
    }
}
