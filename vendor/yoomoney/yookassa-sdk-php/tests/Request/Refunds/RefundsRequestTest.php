<?php

namespace Tests\YooKassa\Request\Refunds;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Request\Refunds\RefundsRequest;
use YooKassa\Request\Refunds\RefundsRequestBuilder;

/**
 * @internal
 */
class RefundsRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPaymentId($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasPaymentId());
        self::assertNull($instance->getPaymentId());
        self::assertNull($instance->paymentId);

        $instance->setPaymentId($options['payment_id']);
        if (empty($options['payment_id'])) {
            self::assertFalse($instance->hasPaymentId());
            self::assertNull($instance->getPaymentId());
            self::assertNull($instance->paymentId);
        } else {
            self::assertTrue($instance->hasPaymentId());
            self::assertEquals($options['payment_id'], $instance->getPaymentId());
            self::assertEquals($options['payment_id'], $instance->paymentId);
        }

        $instance->setPaymentId('');
        self::assertFalse($instance->hasPaymentId());
        self::assertNull($instance->getPaymentId());
        self::assertNull($instance->paymentId);

        $instance->paymentId = $options['payment_id'];
        if (empty($options['payment_id'])) {
            self::assertFalse($instance->hasPaymentId());
            self::assertNull($instance->getPaymentId());
            self::assertNull($instance->paymentId);
        } else {
            self::assertTrue($instance->hasPaymentId());
            self::assertEquals($options['payment_id'], $instance->getPaymentId());
            self::assertEquals($options['payment_id'], $instance->paymentId);
        }
    }

    /**
     * @dataProvider invalidPaymentIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setPaymentId($value);
    }

    /**
     * @dataProvider invalidPaymentIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->paymentId = $value;
    }

    public function validStringDataProvider(): array
    {
        return [
            [[]],
            [true],
            [false],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCreateGte($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasCreatedAtGte());
        self::assertNull($instance->getCreatedAtGte());
        self::assertNull($instance->createdAtGte);

        $instance->setCreatedAtGte($options['created_at_gte']);
        if (empty($options['created_at_gte'])) {
            self::assertFalse($instance->hasCreatedAtGte());
            self::assertNull($instance->getCreatedAtGte());
            self::assertNull($instance->createdAtGte);
        } else {
            self::assertTrue($instance->hasCreatedAtGte());
            self::assertEquals($options['created_at_gte'], $instance->getCreatedAtGte()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_gte'], $instance->createdAtGte->format(YOOKASSA_DATE));
        }

        $instance->setCreatedAtGte('');
        self::assertFalse($instance->hasCreatedAtGte());
        self::assertNull($instance->getCreatedAtGte());
        self::assertNull($instance->createdAtGte);

        $instance->createdAtGte = $options['created_at_gte'];
        if (empty($options['created_at_gte'])) {
            self::assertFalse($instance->hasCreatedAtGte());
            self::assertNull($instance->getCreatedAtGte());
            self::assertNull($instance->createdAtGte);
        } else {
            self::assertTrue($instance->hasCreatedAtGte());
            self::assertEquals($options['created_at_gte'], $instance->getCreatedAtGte()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_gte'], $instance->createdAtGte->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedGte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setCreatedAtGte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCreatedGte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->createdAtGte = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCreateGt($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasCreatedAtGt());
        self::assertNull($instance->getCreatedAtGt());
        self::assertNull($instance->createdAtGt);

        $instance->setCreatedAtGt($options['created_at_gt']);
        if (empty($options['created_at_gt'])) {
            self::assertFalse($instance->hasCreatedAtGte());
            self::assertNull($instance->getCreatedAtGte());
            self::assertNull($instance->createdAtGte);
        } else {
            self::assertTrue($instance->hasCreatedAtGt());
            self::assertEquals($options['created_at_gt'], $instance->getCreatedAtGt()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_gt'], $instance->createdAtGt->format(YOOKASSA_DATE));
        }

        $instance->setCreatedAtGt('');
        self::assertFalse($instance->hasCreatedAtGt());
        self::assertNull($instance->getCreatedAtGt());
        self::assertNull($instance->createdAtGt);

        $instance->createdAtGt = $options['created_at_gt'];
        if (empty($options['created_at_gt'])) {
            self::assertFalse($instance->hasCreatedAtGt());
            self::assertNull($instance->getCreatedAtGt());
            self::assertNull($instance->createdAtGt);
        } else {
            self::assertTrue($instance->hasCreatedAtGt());
            self::assertEquals($options['created_at_gt'], $instance->getCreatedAtGt()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_gt'], $instance->createdAtGt->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedGt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setCreatedAtGt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCreatedGt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->createdAtGt = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCreateLte($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasCreatedAtLte());
        self::assertNull($instance->getCreatedAtLte());
        self::assertNull($instance->createdAtLte);

        $instance->setCreatedAtLte($options['created_at_lte']);
        if (empty($options['created_at_lte'])) {
            self::assertFalse($instance->hasCreatedAtLte());
            self::assertNull($instance->getCreatedAtLte());
            self::assertNull($instance->createdAtLte);
        } else {
            self::assertTrue($instance->hasCreatedAtLte());
            self::assertEquals($options['created_at_lte'], $instance->getCreatedAtLte()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_lte'], $instance->createdAtLte->format(YOOKASSA_DATE));
        }

        $instance->setCreatedAtLte('');
        self::assertFalse($instance->hasCreatedAtLte());
        self::assertNull($instance->getCreatedAtLte());
        self::assertNull($instance->createdAtLte);

        $instance->createdAtLte = $options['created_at_lte'];
        if (empty($options['created_at_lte'])) {
            self::assertFalse($instance->hasCreatedAtLte());
            self::assertNull($instance->getCreatedAtLte());
            self::assertNull($instance->createdAtLte);
        } else {
            self::assertTrue($instance->hasCreatedAtLte());
            self::assertEquals($options['created_at_lte'], $instance->getCreatedAtLte()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_lte'], $instance->createdAtLte->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedLte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setCreatedAtLte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCreatedLte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->createdAtLte = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCreateLt($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasCreatedAtLt());
        self::assertNull($instance->getCreatedAtLt());
        self::assertNull($instance->createdAtLt);

        $instance->setCreatedAtLt($options['created_at_lt']);
        if (empty($options['created_at_lt'])) {
            self::assertFalse($instance->hasCreatedAtLt());
            self::assertNull($instance->getCreatedAtLt());
            self::assertNull($instance->createdAtLt);
        } else {
            self::assertTrue($instance->hasCreatedAtLt());
            self::assertEquals($options['created_at_lt'], $instance->getCreatedAtLt()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_lt'], $instance->createdAtLt->format(YOOKASSA_DATE));
        }

        $instance->setCreatedAtLt('');
        self::assertFalse($instance->hasCreatedAtLt());
        self::assertNull($instance->getCreatedAtLt());
        self::assertNull($instance->createdAtLt);

        $instance->createdAtLt = $options['created_at_lt'];
        if (empty($options['created_at_lt'])) {
            self::assertFalse($instance->hasCreatedAtLt());
            self::assertNull($instance->getCreatedAtLt());
            self::assertNull($instance->createdAtLt);
        } else {
            self::assertTrue($instance->hasCreatedAtLt());
            self::assertEquals($options['created_at_lt'], $instance->getCreatedAtLt()->format(YOOKASSA_DATE));
            self::assertEquals($options['created_at_lt'], $instance->createdAtLt->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedLt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setCreatedAtLt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCreatedLt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->createdAtLt = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testStatus($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasStatus());
        self::assertNull($instance->getStatus());
        self::assertNull($instance->status);

        $instance->setStatus($options['status']);
        if (empty($options['status'])) {
            self::assertFalse($instance->hasStatus());
            self::assertNull($instance->getStatus());
            self::assertNull($instance->status);
        } else {
            self::assertTrue($instance->hasStatus());
            self::assertEquals($options['status'], $instance->getStatus());
            self::assertEquals($options['status'], $instance->status);
        }

        $instance->setStatus('');
        self::assertFalse($instance->hasStatus());
        self::assertNull($instance->getStatus());
        self::assertNull($instance->status);

        $instance->status = $options['status'];
        if (empty($options['status'])) {
            self::assertFalse($instance->hasStatus());
            self::assertNull($instance->getStatus());
            self::assertNull($instance->status);
        } else {
            self::assertTrue($instance->hasStatus());
            self::assertEquals($options['status'], $instance->getStatus());
            self::assertEquals($options['status'], $instance->status);
        }
    }

    /**
     * @dataProvider invalidStatusDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setStatus($value);
    }

    /**
     * @dataProvider invalidStatusDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->status = $value;
        self::assertEquals($value, $instance->status);
    }

    /**
     * @dataProvider validLimitDataProvider
     *
     * @param mixed $value
     */
    public function testLimit($value): void
    {
        $instance = new RefundsRequest();
        $instance->limit = $value;
        self::assertEquals($value, $instance->limit);
    }

    /**
     * @dataProvider invalidLimitDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLimit($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new RefundsRequest();
        $instance->setLimit($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCursor($options): void
    {
        $instance = new RefundsRequest();

        self::assertFalse($instance->hasCursor());
        self::assertNull($instance->getCursor());
        self::assertNull($instance->cursor);

        $instance->setCursor($options['cursor']);
        if (empty($options['cursor'])) {
            self::assertFalse($instance->hasCursor());
            self::assertNull($instance->getCursor());
            self::assertNull($instance->cursor);
        } else {
            self::assertTrue($instance->hasCursor());
            self::assertEquals($options['cursor'], $instance->getCursor());
            self::assertEquals($options['cursor'], $instance->cursor);
        }

        $instance->setCursor('');
        self::assertFalse($instance->hasCursor());
        self::assertNull($instance->getCursor());
        self::assertNull($instance->cursor);

        $instance->cursor = $options['cursor'];
        if (empty($options['cursor'])) {
            self::assertFalse($instance->hasCursor());
            self::assertNull($instance->getCursor());
            self::assertNull($instance->cursor);
        } else {
            self::assertTrue($instance->hasCursor());
            self::assertEquals($options['cursor'], $instance->getCursor());
            self::assertEquals($options['cursor'], $instance->cursor);
        }
    }

    public function testValidate(): void
    {
        $instance = new RefundsRequest();

        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = RefundsRequest::builder();
        self::assertInstanceOf(RefundsRequestBuilder::class, $builder);
    }

    public function validDataProvider()
    {
        $result = [
            [
                [
                    'created_at_gte' => null,
                    'created_at_gt' => null,
                    'created_at_lte' => null,
                    'created_at_lt' => null,
                    'status' => null,
                    'payment_id' => null,
                    'limit' => null,
                    'cursor' => null,
                ],
            ],
            [
                [
                    'created_at_gte' => '',
                    'created_at_gt' => '',
                    'created_at_lte' => '',
                    'created_at_lt' => '',
                    'status' => '',
                    'payment_id' => '',
                    'limit' => '',
                    'cursor' => '',
                ],
            ],
        ];
        $statuses = RefundStatus::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'created_at_gte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'created_at_gt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'created_at_lte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'created_at_lt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'status' => $statuses[Random::int(0, count($statuses) - 1)],
                'payment_id' => $this->randomString(36),
                'limit' => Random::int(0, RefundsRequest::MAX_LIMIT_VALUE),
                'cursor' => uniqid('', true),
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidStatusDataProvider()
    {
        return [
            [true],
            [Random::str(1, 10)],
            [new StringObject(Random::str(1, 10))],
        ];
    }

    public static function validLimitDataProvider()
    {
        return [
            [null],
            [Random::int(1, RefundsRequest::MAX_LIMIT_VALUE)],
        ];
    }

    public static function invalidLimitDataProvider()
    {
        return [
            [[]],
            [new stdClass()],
            [-1],
            [RefundsRequest::MAX_LIMIT_VALUE + 1],
        ];
    }

    public function invalidDataProvider()
    {
        return [
            [[]],
            [new stdClass()],
            [Random::str(10)],
            [Random::bytes(10)],
            [-1],
            [RefundsRequest::MAX_LIMIT_VALUE + 1],
        ];
    }

    public static function invalidPaymentIdDataProvider()
    {
        return [
            [true],
            [Random::str(35)],
            [Random::str(37)],
            [new StringObject(Random::str(10))],
        ];
    }

    public static function invalidDateDataProvider()
    {
        return [
            [true],
            [false],
            [[]],
            [new stdClass()],
            [Random::str(35)],
            [Random::str(37)],
            [new StringObject(Random::str(10))],
            [-123],
        ];
    }

    private function randomString($length, $any = true)
    {
        static $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-+_.';

        $result = '';
        for ($i = 0; $i < $length; $i++) {
            if ($any) {
                $char = chr(Random::int(32, 126));
            } else {
                $rnd = Random::int(0, strlen($chars) - 1);
                $char = $chars[$rnd];
            }
            $result .= $char;
        }

        return $result;
    }
}
