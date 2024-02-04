<?php

namespace Tests\YooKassa\Request\Payouts;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payout\IncomeReceipt;
use YooKassa\Request\Payouts\IncomeReceiptData;

/**
 * @internal
 */
class IncomeReceiptDataTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetServiceName(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['service_name'], $instance->getServiceName());
    }

    public static function validDataProvider(): array
    {
        $result = [];

        for ($i = 0; $i < 10; $i++) {
            $deal = [
                'service_name' => Random::str(36, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
            ];
            $result[] = [$deal];
        }

        return $result;
    }

    /**
     * @dataProvider invalidServiceNameDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidServiceName($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new IncomeReceiptData();
        $instance->setServiceName($value);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidServiceNameDataProvider(): array
    {
        return [
            [''],
            [false],
            [Random::str(IncomeReceipt::MAX_LENGTH_SERVICE_NAME + 1, 60)],
        ];
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmountToken($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new IncomeReceiptData();
        $instance->setAmount($value);
    }

    public static function invalidAmountDataProvider(): array
    {
        return [
            [false],
            [true],
            [new stdClass()],
        ];
    }

    /**
     * @param mixed $options
     */
    protected function getTestInstance($options): IncomeReceiptData
    {
        return new IncomeReceiptData($options);
    }
}
