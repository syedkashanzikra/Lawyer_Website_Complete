<?php

namespace Tests\YooKassa\Request\Receipts;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Model\Receipt\ReceiptType;
use YooKassa\Model\Receipt\SettlementType;
use YooKassa\Request\Receipts\ReceiptResponseFactory;

/**
 * @internal
 */
class ReceiptResponseFactoryTest extends TestCase
{
    /**
     * @dataProvider invalidFactoryDataProvider
     */
    public function testInvalidFactory(array $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseFactory();
        $instance->factory($value);
    }

    public static function invalidFactoryDataProvider()
    {
        return [
            [[]],
            [
                ['type' => new stdClass()]],
            [
                ['type' => SettlementType::POSTPAYMENT]],
            [
                [
                    'type' => ReceiptType::PAYMENT,
                    'refund_id' => 1,
                ],
            ],
            [
                [
                    'type' => ReceiptType::PAYMENT,
                    'payment_id' => 1,
                ],
            ],
            [
                [
                    'type' => ReceiptType::REFUND,
                    'payment_id' => 1,
                ],
            ],
        ];
    }
}
