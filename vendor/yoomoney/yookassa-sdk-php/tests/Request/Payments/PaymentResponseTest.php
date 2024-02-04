<?php

namespace Tests\YooKassa\Request\Payments;

use YooKassa\Request\Payments\PaymentResponse;

/**
 * @internal
 */
class PaymentResponseTest extends AbstractTestPaymentResponse
{
    protected function getTestInstance($options): PaymentResponse
    {
        return new PaymentResponse($options);
    }
}
