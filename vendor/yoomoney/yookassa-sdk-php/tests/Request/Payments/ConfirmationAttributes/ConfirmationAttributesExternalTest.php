<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesExternal;

/**
 * @internal
 */
class ConfirmationAttributesExternalTest extends AbstractTestConfirmationAttributes
{
    protected function getTestInstance(): ConfirmationAttributesExternal
    {
        return new ConfirmationAttributesExternal();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::EXTERNAL;
    }
}
