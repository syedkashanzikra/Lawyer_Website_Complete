<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\ConfirmationAttributes\AbstractConfirmationAttributes;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesEmbedded;

/**
 * @internal
 */
class ConfirmationAttributesEmbeddedTest extends AbstractTestConfirmationAttributes
{
    protected function getTestInstance(): AbstractConfirmationAttributes
    {
        return new ConfirmationAttributesEmbedded();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::EMBEDDED;
    }
}
