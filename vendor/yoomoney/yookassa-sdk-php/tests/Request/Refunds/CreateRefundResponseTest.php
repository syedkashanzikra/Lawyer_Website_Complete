<?php

namespace Tests\YooKassa\Request\Refunds;

use YooKassa\Request\Refunds\CreateRefundResponse;

/**
 * @internal
 */
class CreateRefundResponseTest extends AbstractTestRefundResponse
{
    protected function getTestInstance(array $options): CreateRefundResponse
    {
        return new CreateRefundResponse($options);
    }
}
