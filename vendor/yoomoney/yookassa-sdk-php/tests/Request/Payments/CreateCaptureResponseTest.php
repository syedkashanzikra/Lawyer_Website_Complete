<?php

namespace Tests\YooKassa\Request\Payments;

use YooKassa\Request\Payments\CreateCaptureResponse;

/**
 * @internal
 */
class CreateCaptureResponseTest extends AbstractTestPaymentResponse
{
    protected function getTestInstance($options): CreateCaptureResponse
    {
        return new CreateCaptureResponse($options);
    }
}
