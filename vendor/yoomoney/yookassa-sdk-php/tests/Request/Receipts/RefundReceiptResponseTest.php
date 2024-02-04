<?php

namespace Tests\YooKassa\Request\Receipts;

use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Request\Receipts\RefundReceiptResponse;

/**
 * @internal
 */
class RefundReceiptResponseTest extends AbstractTestReceiptResponse
{
    protected string $type = 'refund';

    /**
     * @dataProvider validDataProvider
     */
    public function testSpecificProperties(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['refund_id'], $instance->getRefundId());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidSpecificProperties(array $options): void
    {
        $this->expectException(InvalidPropertyValueException::class);
        $this->getTestInstance($options);
    }

    protected function getTestInstance($options): RefundReceiptResponse
    {
        return new RefundReceiptResponse($options);
    }

    protected function addSpecificProperties($options, $i): array
    {
        $array = [
            Random::str(30),
            Random::str(40),
        ];
        $options['refund_id'] = !$this->valid
            ? (Random::value($array))
            : Random::value([null, '', Random::str(RefundReceiptResponse::LENGTH_REFUND_ID)]);

        return $options;
    }
}
