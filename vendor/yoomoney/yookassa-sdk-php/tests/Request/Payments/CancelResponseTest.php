<?php

namespace Tests\YooKassa\Request\Payments;

use YooKassa\Request\Payments\CancelResponse;

/**
 * @internal
 */
class CancelResponseTest extends AbstractTestPaymentResponse
{
    protected function getTestInstance($options): CancelResponse
    {
        return new CancelResponse($options);
    }
}
