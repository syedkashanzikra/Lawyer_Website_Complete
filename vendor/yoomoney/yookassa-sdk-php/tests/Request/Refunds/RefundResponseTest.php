<?php

namespace Tests\YooKassa\Request\Refunds;

use YooKassa\Request\Refunds\RefundResponse;

/**
 * @internal
 */
class RefundResponseTest extends AbstractTestRefundResponse
{
    protected function getTestInstance(array $options): RefundResponse
    {
        return new RefundResponse($options);
    }
}
