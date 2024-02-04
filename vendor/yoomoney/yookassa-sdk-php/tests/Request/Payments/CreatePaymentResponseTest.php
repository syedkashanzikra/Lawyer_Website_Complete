<?php

namespace Tests\YooKassa\Request\Payments;

use YooKassa\Request\Payments\CreatePaymentResponse;

/**
 * @internal
 */
class CreatePaymentResponseTest extends AbstractTestPaymentResponse
{
    protected function getTestInstance($options): CreatePaymentResponse
    {
        return new CreatePaymentResponse($options);
    }
}
