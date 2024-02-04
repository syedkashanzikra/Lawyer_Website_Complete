<?php

namespace Tests\YooKassa\Model\Deal;

use DateTime;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Deal\DealBalanceAmount;
use YooKassa\Model\Deal\DealStatus;
use YooKassa\Model\Deal\DealType;
use YooKassa\Model\Deal\FeeMoment;
use YooKassa\Model\Deal\SafeDeal;
use YooKassa\Model\Metadata;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;

/**
 * @internal
 */
class SafeDealTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new SafeDeal();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetType(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setType($options['type']);
        self::assertEquals($options['type'], $instance->getType());
        self::assertEquals($options['type'], $instance->type);

        $instance = new SafeDeal();
        $instance->type = $options['type'];
        self::assertEquals($options['type'], $instance->getType());
        self::assertEquals($options['type'], $instance->type);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetStatus(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new SafeDeal();
        $instance->status = $options['status'];
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetBalance(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setBalance($options['balance']);
        self::assertSame($options['balance'], $instance->getBalance());
        self::assertSame($options['balance'], $instance->balance);

        $instance = new SafeDeal();
        $instance->balance = $options['balance'];
        self::assertSame($options['balance'], $instance->getBalance());
        self::assertSame($options['balance'], $instance->balance);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidBalance($value): void
    {
        if (empty($value['balance'])) {
            $this->expectException(EmptyPropertyValueException::class);
            $instance = new SafeDeal();
            $instance->setBalance($value['balance']);
        } elseif (!is_array($value['balance']) && !($value['balance'] instanceof DealBalanceAmount)) {
            $this->expectException(InvalidPropertyValueTypeException::class);
            $instance = new SafeDeal();
            $instance->setBalance($value['balance']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidBalance($value): void
    {
        if (empty($value['balance'])) {
            $this->expectException(EmptyPropertyValueException::class);
            $instance = new SafeDeal();
            $instance->balance = $value['balance'];
        } elseif (!is_array($value['balance']) && !($value['balance'] instanceof DealBalanceAmount)) {
            $this->expectException(InvalidPropertyValueTypeException::class);
            $instance = new SafeDeal();
            $instance->balance = $value['balance'];
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetPayoutBalance(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setPayoutBalance($options['payout_balance']);
        self::assertSame($options['payout_balance'], $instance->getPayoutBalance());
        self::assertSame($options['payout_balance'], $instance->payout_balance);
        self::assertSame($options['payout_balance'], $instance->payoutBalance);

        $instance = new SafeDeal();
        $instance->payout_balance = $options['payout_balance'];
        self::assertSame($options['payout_balance'], $instance->getPayoutBalance());
        self::assertSame($options['payout_balance'], $instance->payout_balance);
        self::assertSame($options['payout_balance'], $instance->payoutBalance);

        $instance = new SafeDeal();
        $instance->payoutBalance = $options['payout_balance'];
        self::assertSame($options['payout_balance'], $instance->getPayoutBalance());
        self::assertSame($options['payout_balance'], $instance->payout_balance);
        self::assertSame($options['payout_balance'], $instance->payoutBalance);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPayoutBalance($value): void
    {
        if (empty($value['payout_balance'])) {
            $this->expectException(EmptyPropertyValueException::class);
            $instance = new SafeDeal();
            $instance->setPayoutBalance($value['payout_balance']);
        } elseif (!is_array($value['payout_balance']) && !($value['payout_balance'] instanceof DealBalanceAmount)) {
            $this->expectException(InvalidPropertyValueTypeException::class);
            $instance = new SafeDeal();
            $instance->setPayoutBalance($value['payout_balance']);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPayoutBalance($value): void
    {
        if (empty($value['payout_balance'])) {
            $this->expectException(EmptyPropertyValueException::class);
            $instance = new SafeDeal();
            $instance->payout_balance = $value['payout_balance'];
        } elseif (!is_array($value['payout_balance']) && !($value['payout_balance'] instanceof DealBalanceAmount)) {
            $this->expectException(InvalidPropertyValueTypeException::class);
            $instance = new SafeDeal();
            $instance->payout_balance = $value['payout_balance'];
        }
    }

    /**
     * @dataProvider invalidMetaDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMetadata($value): void
    {
        if (!is_array($value) && !($value instanceof Metadata)) {
            $this->expectException(InvalidPropertyValueTypeException::class);
            $instance = new SafeDeal();
            $instance->setMetadata($value);
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetDescription(array $options): void
    {
        $instance = new SafeDeal();
        $instance->setDescription($options['description']);

        if (is_null($options['description']) && ('0' !== $options['description'])) {
            self::assertNull($instance->getDescription());
        } else {
            self::assertEquals($options['description'], $instance->getDescription());
        }
    }

    public function testSetInvalidLengthDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $description = Random::str(SafeDeal::MAX_LENGTH_DESCRIPTION + 1);
        $instance->setDescription($description);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetFeeMoment(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setFeeMoment($options['fee_moment']);
        self::assertEquals($options['fee_moment'], $instance->getFeeMoment());
        self::assertEquals($options['fee_moment'], $instance->fee_moment);
        self::assertEquals($options['fee_moment'], $instance->feeMoment);

        $instance = new SafeDeal();
        $instance->fee_moment = $options['fee_moment'];
        self::assertEquals($options['fee_moment'], $instance->getFeeMoment());
        self::assertEquals($options['fee_moment'], $instance->fee_moment);
        self::assertEquals($options['fee_moment'], $instance->feeMoment);

        $instance = new SafeDeal();
        $instance->feeMoment = $options['fee_moment'];
        self::assertEquals($options['fee_moment'], $instance->getFeeMoment());
        self::assertEquals($options['fee_moment'], $instance->fee_moment);
        self::assertEquals($options['fee_moment'], $instance->feeMoment);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCreatedAt(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new SafeDeal();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new SafeDeal();
        $instance->created_at = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $instance->setCreatedAt($value['created_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCreatedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $instance->createdAt = $value['created_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeCreatedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetExpiresAt(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setExpiresAt($options['expires_at']);
        if (null === $options['expires_at'] || '' === $options['expires_at']) {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expiresAt);
            self::assertNull($instance->expires_at);
        } else {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        }

        $instance = new SafeDeal();
        $instance->expiresAt = $options['expires_at'];
        if (null === $options['expires_at'] || '' === $options['expires_at']) {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expiresAt);
            self::assertNull($instance->expires_at);
        } else {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        }

        $instance = new SafeDeal();
        $instance->expires_at = $options['expires_at'];
        if (null === $options['expires_at'] || '' === $options['expires_at']) {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expiresAt);
            self::assertNull($instance->expires_at);
        } else {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $instance->setExpiresAt($value['expires_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $instance->expiresAt = $value['expires_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SafeDeal();
        $instance->expires_at = $value['expires_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetTest(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setTest($options['test']);
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);

        $instance = new SafeDeal();
        $instance->test = $options['test'];
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMetadata(array $options): void
    {
        $instance = new SafeDeal();

        $instance->setMetadata($options['metadata']);
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);

        $instance = new SafeDeal();
        $instance->metadata = $options['metadata'];
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);
    }

    /**
     * @dataProvider fromArrayDataProvider
     */
    public function testFromArray(array $source, SafeDeal $expected): void
    {
        $dealArray = $expected->toArray();

        if (!empty($source)) {
            foreach ($source as $property => $value) {
                self::assertEquals($value, $dealArray[$property]);
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str(36),
                'type' => Random::value(DealType::getValidValues()),
                'fee_moment' => Random::value(FeeMoment::getValidValues()),
                'status' => Random::value(DealStatus::getValidValues()),
                'balance' => new DealBalanceAmount(Random::int(1, 10000), 'RUB'),
                'payout_balance' => new DealBalanceAmount(Random::int(1, 10000), 'RUB'),
                'description' => (0 === $i ? null : (1 === $i ? '' : (2 === $i ? Random::str(SafeDeal::MAX_LENGTH_DESCRIPTION)
                    : Random::str(1, SafeDeal::MAX_LENGTH_DESCRIPTION)))),
                'created_at' => date(YOOKASSA_DATE, Random::int(1000000, time())),
                'expires_at' => date(YOOKASSA_DATE, Random::int(1111111, time())),
                'test' => (bool) ($i % 2),
                'metadata' => (($i % 2) ? null : new Metadata()),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    /**
     * @return \array[][]
     * @throws Exception
     */
    public static function invalidDataProvider(): array
    {
        $result = [
            [
                [
                    'id' => null,
                    'status' => null,
                    'balance' => null,
                    'payout_balance' => null,
                    'test' => null,
                    'created_at' => null,
                    'expires_at' => null,
                    'metadata' => new stdClass(),
                ],
            ],
            [
                [
                    'id' => '',
                    'status' => '',
                    'balance' => '',
                    'payout_balance' => '',
                    'test' => '',
                    'created_at' => '23423-234-234',
                    'expires_at' => '23423-234-234',
                    'metadata' => true,
                ],
            ],
        ];
        $invalidDateTimeData = [
            null,
            '',
            'invalid_value',
            Random::str(5, 10),
        ];
        $invalidData = [
            null,
            '',
            new stdClass(),
            'invalid_value',
            new Metadata(),
            Random::str(5, 10),
        ];
        $invalidObjectData = [
            null,
            '',
            new stdClass(),
            new Metadata(),
            new DateTime(),
            new MonetaryAmount(['value' => Random::float(0.01, 99.99)])
        ];
        for ($i = 0; $i < 6; $i++) {
            $payment = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(37, 64)),
                'status' => $invalidData[$i],
                'balance' => $invalidObjectData[$i],
                'payout_balance' => $invalidObjectData[$i],
                'test' => $invalidData[$i],
                'created_at' => $invalidDateTimeData[Random::int(0, 3)],
                'expires_at' => $invalidDateTimeData[Random::int(0, 3)],
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function fromArrayDataProvider(): array
    {
        $customer = new SafeDeal();
        $customer->setId('dl-285e5ee7-0022-5000-8000-01516a44b147');
        $customer->setStatus(DealStatus::OPENED);
        $customer->setBalance(new DealBalanceAmount(1000, 'RUB'));
        $customer->setPayoutBalance(new DealBalanceAmount(1000, 'RUB'));
        $customer->setDescription('Выплата по заказу №17');
        $customer->setCreatedAt(new DateTime(date(YOOKASSA_DATE)));
        $customer->setExpiresAt(date(YOOKASSA_DATE));
        $customer->setTest(true);
        $customer->setMetadata(['order_id' => 'Заказ №17']);
        $customer->setType(DealType::SAFE_DEAL);

        return [
            [
                [
                    'id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147',
                    'type' => DealType::SAFE_DEAL,
                    'status' => DealStatus::OPENED,
                    'balance' => ['value' => 1000, 'currency' => 'RUB'],
                    'payout_balance' => ['value' => 1000, 'currency' => 'RUB'],
                    'description' => 'Выплата по заказу №17',
                    'created_at' => date(YOOKASSA_DATE),
                    'expires_at' => date(YOOKASSA_DATE),
                    'test' => true,
                    'metadata' => [
                        'order_id' => 'Заказ №17',
                    ],
                ],
                $customer,
            ],
        ];
    }

    /**
     * @return array
     */
    public static function invalidMetaDataProvider(): array
    {
        return [
            [new stdClass()],
            ['invalid_value'],
            [0],
            [3234],
            [true],
            [false],
            [0.43],
        ];
    }
}
