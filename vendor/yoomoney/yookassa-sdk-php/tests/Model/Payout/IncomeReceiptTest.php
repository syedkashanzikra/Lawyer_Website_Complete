<?php

namespace Tests\YooKassa\Model\Payout;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout\IncomeReceipt;

/**
 * @internal
 */
class IncomeReceiptTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetAmount($options): void
    {
        $instance = new IncomeReceipt();

        $instance->setAmount($options['amount']);
        if (empty($options['amount'])) {
            self::assertNull($instance->getAmount());
            self::assertNull($instance->amount);
        } else {
            if (is_array($options['amount'])) {
                self::assertEquals($options['amount'], $instance->getAmount()->toArray());
                self::assertEquals($options['amount'], $instance->amount->toArray());
            } else {
                self::assertEquals($options['amount'], $instance->getAmount());
                self::assertEquals($options['amount'], $instance->amount);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterAmount($options): void
    {
        $instance = new IncomeReceipt();

        $instance->amount = $options['amount'];
        if (empty($options['amount'])) {
            self::assertNull($instance->getAmount());
            self::assertNull($instance->amount);
        } else {
            if (is_array($options['amount'])) {
                self::assertEquals($options['amount'], $instance->getAmount()->toArray());
                self::assertEquals($options['amount'], $instance->amount->toArray());
            } else {
                self::assertEquals($options['amount'], $instance->getAmount());
                self::assertEquals($options['amount'], $instance->amount);
            }
        }
    }

    /**
     * @dataProvider invalidAmountProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new IncomeReceipt();
        $instance->setAmount($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetServiceName($options): void
    {
        $instance = new IncomeReceipt();

        $instance->setServiceName($options['service_name']);
        self::assertEquals($options['service_name'], $instance->getServiceName());
        self::assertEquals($options['service_name'], $instance->service_name);
        self::assertEquals($options['service_name'], $instance->serviceName);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterServiceName($options): void
    {
        $instance = new IncomeReceipt();

        $instance->service_name = $options['service_name'];
        self::assertEquals($options['service_name'], $instance->getServiceName());
        self::assertEquals($options['service_name'], $instance->service_name);
        self::assertEquals($options['service_name'], $instance->serviceName);

        $instance->serviceName = $options['service_name'];
        self::assertEquals($options['service_name'], $instance->getServiceName());
        self::assertEquals($options['service_name'], $instance->service_name);
        self::assertEquals($options['service_name'], $instance->serviceName);
    }

    /**
     * @dataProvider invalidServiceNameProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidServiceName($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new IncomeReceipt();
        $instance->setServiceName($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetNpdReceiptId($options): void
    {
        $instance = new IncomeReceipt();

        $instance->setNpdReceiptId($options['npd_receipt_id']);
        self::assertEquals($options['npd_receipt_id'], $instance->getNpdReceiptId());
        self::assertEquals($options['npd_receipt_id'], $instance->npd_receipt_id);
        self::assertEquals($options['npd_receipt_id'], $instance->npdReceiptId);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterNpdReceiptId($options): void
    {
        $instance = new IncomeReceipt();

        $instance->npd_receipt_id = $options['npd_receipt_id'];
        self::assertEquals($options['npd_receipt_id'], $instance->getNpdReceiptId());
        self::assertEquals($options['npd_receipt_id'], $instance->npd_receipt_id);
        self::assertEquals($options['npd_receipt_id'], $instance->npdReceiptId);

        $instance->npdReceiptId = $options['npd_receipt_id'];
        self::assertEquals($options['npd_receipt_id'], $instance->getNpdReceiptId());
        self::assertEquals($options['npd_receipt_id'], $instance->npd_receipt_id);
        self::assertEquals($options['npd_receipt_id'], $instance->npdReceiptId);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetUrl($options): void
    {
        $instance = new IncomeReceipt();

        $instance->setUrl($options['url']);
        self::assertEquals($options['url'], $instance->getUrl());
        self::assertEquals($options['url'], $instance->url);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterUrl($options): void
    {
        $instance = new IncomeReceipt();

        $instance->url = $options['url'];
        self::assertEquals($options['url'], $instance->getUrl());
        self::assertEquals($options['url'], $instance->url);
    }

    public static function validDataProvider(): array
    {
        $result = [
            [
                [
                    'service_name' => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id' => null,
                    'url' => null,
                    'amount' => null,
                ],
            ],
            [
                [
                    'service_name' => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id' => '',
                    'url' => 'http://test.ru',
                    'amount' => new MonetaryAmount(['value' => Random::float(0.01, 99.99)]),
                ],
            ],
            [
                [
                    'service_name' => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id' => Random::str(1, 50),
                    'url' => '',
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                ],
            ],
        ];
        for ($i = 1; $i < 6; $i++) {
            $receipt = [
                'service_name' => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                'npd_receipt_id' => Random::str(10, 50),
                'url' => 'https://' . Random::str(1, 10, 'abcdefghijklmnopqrstuvwxyz') . '.ru',
                'amount' => [
                    'value' => round(Random::float(0.1, 99.99), 2),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
            ];
            $result[] = [$receipt];
        }

        return $result;
    }

    public static function invalidServiceNameProvider(): array
    {
        return [
            [''],
            [false],
            [Random::str(IncomeReceipt::MAX_LENGTH_SERVICE_NAME + 1, 60)],
        ];
    }

    public static function invalidAmountProvider(): array
    {
        return [
            [new stdClass()],
            [true],
            [false],
        ];
    }
}
