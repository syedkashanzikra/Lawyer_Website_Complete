<?php

namespace Tests\YooKassa\Model\Payment;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\Transfer;
use YooKassa\Model\Payment\TransferStatus;

/**
 * @internal
 */
class TransferTest extends TestCase
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
        self::assertSame($value['status'], $instance->getStatus());
        self::assertSame($value['status'], $instance->status);
        self::assertSame($value['description'], $instance->getDescription());
        self::assertSame($value['description'], $instance->description);
        if (!empty($value['metadata'])) {
            self::assertSame($value['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($value['metadata'], $instance->metadata->toArray());

            self::assertSame($value, $instance->jsonSerialize());
        }
        self::assertSame($value['release_funds'], $instance->releaseFunds);
        self::assertSame($value['release_funds'], $instance->release_funds);
        self::assertSame($value['connected_account_id'], $instance->connectedAccountId);
        self::assertSame($value['connected_account_id'], $instance->connected_account_id);

        self::assertInstanceOf(Transfer::class, $instance);
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
                'status' => TransferStatus::PENDING,
                'description' => 'Заказ маркетплейса №1',
                'metadata' => null,
                'release_funds' => false,
                'connected_account_id' => null,
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
                'status' => Random::value(TransferStatus::getValidValues()),
                'description' => Random::str(2, Transfer::MAX_LENGTH_DESCRIPTION),
                'metadata' => [
                    Random::str(2, 16) => Random::str(2, 512),
                ],
                'release_funds' => Random::bool(),
                'connected_account_id' => Random::str(0, 100),
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

    /**
     * @dataProvider validStatusProvider
     *
     * @param mixed $value
     */
    public function testSetStatus($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setStatus($value);
        self::assertEquals($value, $instance->getStatus());
    }

    /**
     * @dataProvider validStatusProvider
     *
     * @param mixed $value
     */
    public function testSetterStatus($value): void
    {
        $instance = $this->getTestInstance();
        self::assertEquals($instance->status, TransferStatus::PENDING);
        self::assertEquals($instance->getStatus(), TransferStatus::PENDING);
        $instance->status = $value;
        self::assertEquals($value, $instance->status);
        self::assertEquals($value, $instance->getStatus());
    }

    /**
     * @return array[]
     */
    public static function validStatusProvider(): array
    {
        return [
            [TransferStatus::SUCCEEDED],
            [TransferStatus::CANCELED],
            [TransferStatus::WAITING_FOR_CAPTURE],
            [TransferStatus::PENDING],
        ];
    }

    /**
     * @dataProvider invalidStatusProvider
     *
     * @param mixed $value
     */
    public function testGetSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setStatus($value);
    }

    /**
     * @dataProvider invalidStatusProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->status = $value;
    }

    public function testSetInvalidLengthDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Transfer();
        $description = Random::str(Transfer::MAX_LENGTH_DESCRIPTION + 1);
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
     * @dataProvider validDataProvider
     */
    public function testGetSetConnectedAccountId(array $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setConnectedAccountId($value['connected_account_id']);
        self::assertSame($value['connected_account_id'], $instance->getConnectedAccountId());
        self::assertSame($value['connected_account_id'], $instance->connected_account_id);
        self::assertSame($value['connected_account_id'], $instance->connectedAccountId);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testSetterConnectedAccountId(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->connected_account_id = $value['connected_account_id'];
        self::assertSame($value['connected_account_id'], $instance->getConnectedAccountId());
        self::assertSame($value['connected_account_id'], $instance->connected_account_id);
        self::assertSame($value['connected_account_id'], $instance->connectedAccountId);
    }


    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetReleaseFunds(array $options): void
    {
        $instance = $this->getTestInstance();

        $instance->setReleaseFunds($options['release_funds']);
        self::assertSame($options['release_funds'], $instance->getReleaseFunds());
        self::assertSame($options['release_funds'], $instance->release_funds);

        $instance = $this->getTestInstance();
        $instance->release_funds = $options['release_funds'];
        self::assertSame($options['release_funds'], $instance->getReleaseFunds());
        self::assertSame($options['release_funds'], $instance->release_funds);
    }

    /**
     * @throws Exception
     */
    public static function invalidStatusProvider(): array
    {
        return [
            [null],
            [''],
            [Random::str(15, 100)],
        ];
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

    protected function getTestInstance(): Transfer
    {
        return new Transfer();
    }
}
