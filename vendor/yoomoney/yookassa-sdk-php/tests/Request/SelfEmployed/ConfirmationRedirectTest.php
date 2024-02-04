<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationRedirect;

/**
 * @internal
 */
class ConfirmationRedirectTest extends AbstractTestConfirmation
{
    protected function getTestInstance(): SelfEmployedRequestConfirmationRedirect
    {
        return new SelfEmployedRequestConfirmationRedirect();
    }

    protected function getExpectedType(): string
    {
        return SelfEmployedConfirmationType::REDIRECT;
    }
}
