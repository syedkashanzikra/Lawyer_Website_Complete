<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Request\Payments\TransferData;

/**
 * @internal
 */
class TransferDataTest extends TestCase
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
        self::assertSame($value['description'], $instance->getDescription());
        self::assertSame($value['description'], $instance->description);
        if (!empty($value['metadata'])) {
            self::assertSame($value['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($value['metadata'], $instance->metadata->toArray());

            self::assertSame($value, $instance->jsonSerialize());
        }

        self::assertInstanceOf(TransferData::class, $instance);
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
        $result = [
            [
                'account_id' => '123',
                'amount' => [
                    'value' => '10.00',
                    'currency' => 'RUB',
                ],
                'platform_fee_amount' => [
                    'value' => '10.00',
                    'currency' => 'RUB',
                ],
                'description' => 'Заказ маркетплейса №1',
                'metadata' => null,
            ],
        ];
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
                'description' => Random::str(2, TransferData::MAX_LENGTH_DESCRIPTION),
                'metadata' => [
                    Random::str(2, 16) => Random::str(2, 512),
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

    /**
     * @dataProvider invalidMetadataProvider
     *
     * @param mixed $value
     */
    public function testInvalidMetadata($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setMetadata($value);
    }

    public static function invalidAccountIdProvider(): array
    {
        return [
            [null],
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
     * @dataProvider invalidAmountWithoutNullDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPlatformFeeAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setPlatformFeeAmount($value);
    }

    /**
     * @dataProvider invalidAmountWithoutNullDataProvider
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
            [null],
            [new stdClass()],
        ];
    }

    public static function invalidAmountWithoutNullDataProvider(): array
    {
        return [
            [new stdClass()],
        ];
    }

    public function testSetInvalidLengthDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new TransferData();
        $description = Random::str(TransferData::MAX_LENGTH_DESCRIPTION + 1);
        $instance->setDescription($description);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMetadata(array $options): void
    {
        $instance = $this->getTestInstance();

        $instance->setMetadata($options['metadata']);
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);

        $instance = $this->getTestInstance();
        $instance->metadata = $options['metadata'];
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);
    }

    /**
     * @throws Exception
     */
    public static function invalidMetadataProvider(): array
    {
        return [
            [[new stdClass()]],
        ];
    }

    protected function getTestInstance(): TransferData
    {
        return new TransferData();
    }
}
