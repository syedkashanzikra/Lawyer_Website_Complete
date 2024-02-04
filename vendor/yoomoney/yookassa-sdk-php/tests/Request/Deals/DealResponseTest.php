<?php

namespace Tests\YooKassa\Request\Deals;

use YooKassa\Request\Deals\DealResponse;

/**
 * @internal
 */
class DealResponseTest extends AbstractTestDealResponse
{
    protected function getTestInstance($options): DealResponse
    {
        return new DealResponse($options);
    }
}
