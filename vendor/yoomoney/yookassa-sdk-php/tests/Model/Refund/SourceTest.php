<?php

namespace Tests\YooKassa\Model\Refund;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Refund\Source;

/**
 * @internal
 */
class SourceTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testFromArray(array $value): void
    {
        $instance = $this->getTestInstance();

        $instance->fromArray($value);

        self::assertSame($value['account_id'], $instance->getAccountId());
        self::assertSame($value['account_id'], $instance->accountId);
        self::assertSame($value['amount'], $instance->getAmount()->jsonSerialize());
        self::assertSame($value['amount'], $instance->amount->jsonSerialize());
        self::assertSame($value['platform_fee_amount'], $instance->getPlatformFeeAmount()->jsonSerialize());
        self::assertSame($value['platform_fee_amount'], $instance->platform_fee_amount->jsonSerialize());
        self::assertTrue($instance->hasAmount());
        self::assertTrue($instance->hasPlatformFeeAmount());

        self::assertSame($value, $instance->jsonSerialize());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetAccountId(array $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAccountId($value['account_id']);
        self::assertSame($value['account_id'], $instance->getAccountId());
        self::assertSame($value['account_id'], $instance->accountId);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testSetterAccountId(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->accountId = $value['account_id'];
        self::assertSame($value['account_id'], $instance->getAccountId());
        self::assertSame($value['account_id'], $instance->accountId);
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [
                'account_id' => (string) Random::int(11111111, 99999999),
                'amount' => [
                    'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'platform_fee_amount' => [
                    'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
            ];
        }

        return [$result];
    }

    /**
     * @dataProvider invalidAccountIdProvider
     *
     * @param mixed $value
     */
    public function testGetSetInvalidAccountId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setAccountId($value);
    }

    /**
     * @dataProvider invalidAccountIdProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAccountId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->accountId = $value;
    }

    public static function invalidAccountIdProvider(): array
    {
        return [
            [null],
            [''],
            [[]],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider validAmountDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetAmount($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAmount($value);
        self::assertSame($value, $instance->getAmount());
        self::assertSame($value, $instance->amount);
    }

    /**
     * @dataProvider validAmountDataProvider
     */
    public function testSetterAmount(AmountInterface $value): void
    {
        $instance = $this->getTestInstance();
        $instance->amount = $value;
        self::assertSame($value, $instance->getAmount());
        self::assertSame($value, $instance->amount);
    }

    /**
     * @dataProvider validAmountDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetPlatformFeeAmount($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setPlatformFeeAmount($value);
        self::assertSame($value, $instance->getPlatformFeeAmount());
        self::assertSame($value, $instance->platform_fee_amount);
    }

    /**
     * @dataProvider validAmountDataProvider
     */
    public function testSetterPlatformFeeAmount(AmountInterface $value): void
    {
        $instance = $this->getTestInstance();
        $instance->platform_fee_amount = $value;
        self::assertSame($value, $instance->getPlatformFeeAmount());
        self::assertSame($value, $instance->platform_fee_amount);
    }

    /**
     * @return MonetaryAmount[][]
     *
     * @throws Exception
     */
    public static function validAmountDataProvider(): array
    {
        return [
            [
                new MonetaryAmount(
                    Random::int(1, 100),
                    Random::value(CurrencyCode::getValidValues())
                ),
            ],
            [
                new MonetaryAmount(),
            ],
        ];
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setAmount($value);
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->amount = $value;
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPlatformFeeAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setPlatformFeeAmount($value);
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPlatformFeeAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->platform_fee_amount = $value;
    }

    public static function invalidAmountDataProvider(): array
    {
        return [
            ['null'],
            [1.0],
            [1],
            [true],
            [false],
            [new stdClass()],
        ];
    }

    protected function getTestInstance(): Source
    {
        return new Source();
    }
}
