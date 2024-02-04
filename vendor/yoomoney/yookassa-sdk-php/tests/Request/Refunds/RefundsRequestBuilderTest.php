<?php

namespace Tests\YooKassa\Request\Refunds;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Request\Refunds\RefundsRequest;
use YooKassa\Request\Refunds\RefundsRequestBuilder;

/**
 * @internal
 */
class RefundsRequestBuilderTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetPaymentId($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getPaymentId());

        $builder->setPaymentId($options['paymentId']);
        $instance = $builder->build();
        if (empty($options['paymentId'])) {
            self::assertNull($instance->getPaymentId());
        } else {
            self::assertEquals($options['paymentId'], $instance->getPaymentId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetCreateAtGte($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getCreatedAtGte());

        $builder->setCreatedAtGte($options['createAtGte']);
        $instance = $builder->build();
        if (empty($options['createAtGte'])) {
            self::assertNull($instance->getCreatedAtGte());
        } else {
            self::assertEquals($options['createAtGte'], $instance->getCreatedAtGte()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetCreateAtGt($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getCreatedAtGt());

        $builder->setCreatedAtGt($options['createAtGt']);
        $instance = $builder->build();
        if (empty($options['createAtGt'])) {
            self::assertNull($instance->getCreatedAtGt());
        } else {
            self::assertEquals($options['createAtGt'], $instance->getCreatedAtGt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetCreateAtLte($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getCreatedAtLte());

        $builder->setCreatedAtLte($options['createAtLte']);
        $instance = $builder->build();
        if (empty($options['createAtLte'])) {
            self::assertNull($instance->getCreatedAtLte());
        } else {
            self::assertEquals($options['createAtLte'], $instance->getCreatedAtLte()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetCreateAtLt($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getCreatedAtLt());

        $builder->setCreatedAtLt($options['createAtLt']);
        $instance = $builder->build();
        if (empty($options['createAtLt'])) {
            self::assertNull($instance->getCreatedAtLt());
        } else {
            self::assertEquals($options['createAtLt'], $instance->getCreatedAtLt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetStatus($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getStatus());

        $builder->setStatus($options['status']);
        $instance = $builder->build();
        if (empty($options['status'])) {
            self::assertNull($instance->getStatus());
        } else {
            self::assertEquals($options['status'], $instance->getStatus());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetCursor($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getCursor());

        $builder->setCursor($options['cursor']);
        $instance = $builder->build();
        if (empty($options['cursor'])) {
            self::assertNull($instance->getCursor());
        } else {
            self::assertEquals($options['cursor'], $instance->getCursor());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetLimit($options): void
    {
        $builder = new RefundsRequestBuilder();

        $instance = $builder->build();
        self::assertNull($instance->getLimit());

        $builder->setLimit($options['limit']);
        $instance = $builder->build();
        if (empty($options['limit'])) {
            self::assertNull($instance->getLimit());
        } else {
            self::assertEquals($options['limit'], $instance->getLimit());
        }
    }

    public function validDataProvider()
    {
        $result = [
            [
                [
                    'paymentId' => null,
                    'createAtGte' => null,
                    'createAtGt' => null,
                    'createAtLte' => null,
                    'createAtLt' => null,
                    'status' => '',
                    'cursor' => null,
                    'limit' => 1,
                ],
            ],
            [
                [
                    'paymentId' => '',
                    'createAtGte' => '',
                    'createAtGt' => '',
                    'createAtLte' => '',
                    'createAtLt' => '',
                    'status' => '',
                    'cursor' => '',
                    'limit' => null,
                ],
            ],
        ];
        $statuses = RefundStatus::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'paymentId' => $this->randomString(36),
                'createAtGte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createAtGt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createAtLte' => date(YOOKASSA_DATE, Random::int(1, time())),
                'createAtLt' => date(YOOKASSA_DATE, Random::int(1, time())),
                'status' => $statuses[Random::int(0, count($statuses) - 1)],
                'cursor' => uniqid('', true),
                'limit' => Random::int(1, RefundsRequest::MAX_LIMIT_VALUE),
            ];
            $result[] = [$request];
        }

        return $result;
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
