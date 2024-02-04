<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use YooKassa\Model\Payment\PaymentMethod\PaymentMethodWechat;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodWechatTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodWechat
    {
        return new PaymentMethodWechat();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::WECHAT;
    }
}
