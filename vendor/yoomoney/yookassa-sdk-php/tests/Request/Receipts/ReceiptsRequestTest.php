<?php

namespace Tests\YooKassa\Request\Receipts;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Receipt\SettlementType;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Request\Receipts\ReceiptsRequest;
use YooKassa\Request\Receipts\ReceiptsRequestBuilder;

/**
 * @internal
 */
class ReceiptsRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetRefundId($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasRefundId());
        $instance->setRefundId($value['refundId']);
        self::assertEquals($value['refundId'], $instance->getRefundId());
        if (null != $value['refundId']) {
            self::assertTrue($instance->hasRefundId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetPaymentId($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasPaymentId());
        $instance->setPaymentId($value['paymentId']);
        self::assertSame($value['paymentId'], $instance->getPaymentId());
        if (!is_null($value['paymentId'])) {
            self::assertTrue($instance->hasPaymentId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCreatedAtGte($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasCreatedAtGte());
        $instance->setCreatedAtGte($value['createdAtLte']);
        if (!is_null($value['createdAtLte'])) {
            self::assertEquals($value['createdAtLte'], $instance->getCreatedAtGte()->format(YOOKASSA_DATE));
            self::assertTrue($instance->hasCreatedAtGte());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCreatedAtGt($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasCreatedAtGt());
        $instance->setCreatedAtGt($value['createdAtGt']);
        if (!is_null($value['createdAtGt'])) {
            self::assertEquals($value['createdAtGt'], $instance->getCreatedAtGt()->format(YOOKASSA_DATE));
            self::assertTrue($instance->hasCreatedAtGt());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCreatedAtLte($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasCreatedAtGte());
        $instance->setCreatedAtLte($value['createdAtLte']);
        if (!is_null($value['createdAtLte'])) {
            self::assertEquals($value['createdAtLte'], $instance->getCreatedAtLte()->format(YOOKASSA_DATE));
            self::assertTrue($instance->hasCreatedAtLte());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCreatedAtLt($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasCreatedAtLt());
        $instance->setCreatedAtLt($value['createdAtLt']);
        if (!is_null($value['createdAtLt'])) {
            self::assertEquals($value['createdAtLt'], $instance->getCreatedAtLt()->format(YOOKASSA_DATE));
            self::assertTrue($instance->hasCreatedAtLt());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetStatus($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasStatus());
        $instance->setStatus($value['status']);
        if (!is_null($value['status'])) {
            self::assertEquals($value['status'], $instance->getStatus());
            self::assertTrue($instance->hasStatus());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCursor($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasCursor());
        $instance->setCursor($value['cursor']);
        if (!is_null($value['cursor'])) {
            self::assertEquals($value['cursor'], $instance->getCursor());
            self::assertTrue($instance->hasCursor());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetLimit($value): void
    {
        $instance = new ReceiptsRequest();
        self::assertFalse($instance->hasLimit());
        $instance->setLimit($value['limit']);
        if (!is_null($value['limit'])) {
            self::assertEquals($value['limit'], $instance->getLimit());
            self::assertTrue($instance->hasLimit());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $value
     */
    public function testValidate($value): void
    {
        $instance = new ReceiptsRequest();
        $instance->fromArray($value);
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $instance = new ReceiptsRequest();
        self::assertTrue($instance::builder() instanceof ReceiptsRequestBuilder);
    }

    /**
     * @dataProvider invalidLimitDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidLimitData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setLimit($value);
    }

    /**
     * @dataProvider invalidStatusDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidStatusData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setStatus($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidCreatedAtLtData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setCreatedAtLt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidCreatedAtLteData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setCreatedAtLte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidCreatedAtGtData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setCreatedAtGt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidCreatedAtGteData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setCreatedAtGte($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidPaymentIdData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setPaymentId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidRefundIdData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptsRequest();
        $instance->setRefundId($value);
    }

    public static function validDataProvider()
    {
        $result = [
            [
                [
                    'paymentId' => '216749da-000f-50be-b000-096747fad91e',
                    'refundId' => '216749f7-0016-50be-b000-078d43a63ae4',
                    'status' => RefundStatus::SUCCEEDED,
                    'limit' => 100,
                    'cursor' => '37a5c87d-3984-51e8-a7f3-8de646d39ec15',
                    'createdAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'createdAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'createdAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                    'createdAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $receipts = [
                'paymentId' => Random::str(36),
                'refundId' => Random::str(36),
                'createdAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createdAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'status' => Random::value(ReceiptRegistrationStatus::getValidValues()),
                'cursor' => uniqid('', true),
                'limit' => Random::int(1, ReceiptsRequest::MAX_LIMIT_VALUE),
            ];
            $result[] = [$receipts];
        }

        return $result;
    }

    public static function invalidLimitDataProvider()
    {
        return [
            [150],
        ];
    }

    public static function invalidStatusDataProvider()
    {
        return [
            [SettlementType::POSTPAYMENT],
        ];
    }

    public static function invalidDateDataProvider()
    {
        return [
            [SettlementType::POSTPAYMENT],
            [true],
        ];
    }

    public static function invalidIdDataProvider()
    {
        return [
            ['216749f7-0016-50be-b000-078d43a63ae4-sdgb252346'],
            [true],
        ];
    }
}
