<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use YooKassa\Model\Payment\Confirmation\ConfirmationExternal;
use YooKassa\Model\Payment\ConfirmationType;

/**
 * @internal
 */
class ConfirmationExternalTest extends AbstractTestConfirmation
{
    protected function getTestInstance(): ConfirmationExternal
    {
        return new ConfirmationExternal();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::EXTERNAL;
    }
}
