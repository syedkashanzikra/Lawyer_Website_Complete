<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesQr;

/**
 * @internal
 */
class ConfirmationAttributesQrTest extends AbstractTestConfirmationAttributes
{
    protected function getTestInstance(): ConfirmationAttributesQr
    {
        return new ConfirmationAttributesQr();
    }

    protected function getExpectedType(): string
    {
        return ConfirmationType::QR;
    }
}
