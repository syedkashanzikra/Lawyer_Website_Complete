<?php

namespace Tests\YooKassa\Request\Payouts;

use YooKassa\Request\Payouts\PayoutResponse;

/**
 * @internal
 */
class PayoutResponseTest extends AbstractTestPayoutResponse
{
    protected function getTestInstance($options): PayoutResponse
    {
        return new PayoutResponse($options);
    }
}
