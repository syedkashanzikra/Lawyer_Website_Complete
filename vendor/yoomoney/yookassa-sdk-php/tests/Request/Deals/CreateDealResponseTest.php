<?php

namespace Tests\YooKassa\Request\Deals;

use YooKassa\Request\Deals\CreateDealResponse;

/**
 * @internal
 */
class CreateDealResponseTest extends AbstractTestDealResponse
{
    protected function getTestInstance($options): CreateDealResponse
    {
        return new CreateDealResponse($options);
    }
}
