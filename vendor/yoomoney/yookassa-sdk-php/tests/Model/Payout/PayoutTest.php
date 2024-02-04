<?php

namespace Tests\YooKassa\Model\Payout;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\PayoutDealInfo;
use YooKassa\Model\Metadata;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\PaymentMethod\BankCardType;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\IncomeReceipt;
use YooKassa\Model\Payout\Payout;
use YooKassa\Model\Payout\PayoutCancellationDetails;
use YooKassa\Model\Payout\PayoutCancellationDetailsPartyCode;
use YooKassa\Model\Payout\PayoutCancellationDetailsReasonCode;
use YooKassa\Model\Payout\PayoutDestinationFactory;
use YooKassa\Model\Payout\PayoutDestinationType;
use YooKassa\Model\Payout\PayoutSelfEmployed;
use YooKassa\Model\Payout\PayoutStatus;

/**
 * @internal
 */
class PayoutTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new Payout();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new Payout();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setId($value['id']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetStatus(array $options): void
    {
        $instance = new Payout();

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new Payout();
        $instance->status = $options['status'];
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setStatus($value['status']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetAmount(array $options): void
    {
        $instance = new Payout();

        $instance->setAmount($options['amount']);
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);

        $instance = new Payout();
        $instance->amount = $options['amount'];
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setAmount($value['amount']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->amount = $value['amount'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetPayoutDestination(array $options): void
    {
        $instance = new Payout();

        $instance->setPayoutDestination($options['payout_destination']);
        self::assertEquals($options['payout_destination'], $instance->getPayoutDestination());
        self::assertEquals($options['payout_destination'], $instance->payout_destination);

        $instance = new Payout();
        $instance->payout_destination = $options['payout_destination'];
        self::assertEquals($options['payout_destination'], $instance->getPayoutDestination());
        self::assertEquals($options['payout_destination'], $instance->payout_destination);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPayoutDestination($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setPayoutDestination($value['payout_destination']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPayoutDestination($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->payout_destination = $value['payout_destination'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCancellationDetails($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setCancellationDetails($value['cancellation_details']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCancellationDetails($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->cancellation_details = $value['cancellation_details'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCreatedAt(array $options): void
    {
        $instance = new Payout();

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new Payout();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new Payout();
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
        $instance = new Payout();
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
        $instance = new Payout();
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
        $instance = new Payout();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetTest(array $options): void
    {
        $instance = new Payout();

        $instance->setTest($options['test']);
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);

        $instance = new Payout();
        $instance->test = $options['test'];
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetDeal(array $options): void
    {
        $instance = new Payout();

        $instance->setDeal($options['deal']);

        if (empty($options['deal'])) {
            self::assertNull($instance->getSelfEmployed());
            self::assertNull($instance->deal);
        } elseif (is_array($options['deal'])) {
            self::assertSame($options['deal'], $instance->getDeal()->toArray());
            self::assertSame($options['deal'], $instance->deal->toArray());
        } else {
            self::assertSame($options['deal'], $instance->getDeal());
            self::assertSame($options['deal'], $instance->deal);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDeal($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setDeal($value['deal']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidDeal($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->deal = $value['deal'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetSelfEmployed(array $options): void
    {
        $instance = new Payout();

        $instance->setSelfEmployed($options['self_employed']);

        if (empty($options['receipt'])) {
            self::assertNull($instance->getSelfEmployed());
            self::assertNull($instance->self_employed);
            self::assertNull($instance->selfEmployed);
        } elseif (is_array($options['self_employed'])) {
            self::assertSame($options['self_employed'], $instance->getSelfEmployed()->toArray());
            self::assertSame($options['self_employed'], $instance->self_employed->toArray());
            self::assertSame($options['self_employed'], $instance->selfEmployed->toArray());
        } else {
            self::assertSame($options['self_employed'], $instance->getSelfEmployed());
            self::assertSame($options['self_employed'], $instance->self_employed);
            self::assertSame($options['self_employed'], $instance->selfEmployed);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidSelfEmployed($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setSelfEmployed($value['self_employed']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSelfEmployed($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->self_employed = $value['self_employed'];

        $instance = new Payout();
        $instance->selfEmployed = $value['self_employed'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetReceipt(array $options): void
    {
        $instance = new Payout();

        $instance->setReceipt($options['receipt']);

        if (empty($options['receipt'])) {
            self::assertNull($instance->getReceipt());
            self::assertNull($instance->receipt);
        } elseif (is_array($options['receipt'])) {
            self::assertSame($options['receipt'], $instance->getReceipt()->toArray());
            self::assertSame($options['receipt'], $instance->receipt->toArray());
        } else {
            self::assertSame($options['receipt'], $instance->getReceipt());
            self::assertSame($options['receipt'], $instance->receipt);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceipt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->setReceipt($value['receipt']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidReceipt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $instance->receipt = $value['receipt'];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetDescription($options): void
    {
        $instance = new Payout();
        $instance->setDescription($options['description']);

        if (empty($options['description']) && ('0' !== $options['description'])) {
            self::assertNull($instance->getDescription());
        } else {
            self::assertEquals($options['description'], $instance->getDescription());
        }
    }

    public function testSetInvalidLengthDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payout();
        $description = Random::str(Payout::MAX_LENGTH_DESCRIPTION + 1);
        $instance->setDescription($description);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCancellationDetails(array $options): void
    {
        $instance = new Payout();

        $instance->setCancellationDetails($options['cancellation_details']);
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new Payout();
        $instance->cancellationDetails = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new Payout();
        $instance->cancellation_details = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMetadata(array $options): void
    {
        $instance = new Payout();

        if (is_array($options['metadata'])) {
            $instance->setMetadata($options['metadata']);
            self::assertSame($options['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($options['metadata'], $instance->metadata->toArray());

            $instance = new Payout();
            $instance->metadata = $options['metadata'];
            self::assertSame($options['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($options['metadata'], $instance->metadata->toArray());
        } elseif ($options['metadata'] instanceof Metadata || empty($options['metadata'])) {
            $instance->setMetadata($options['metadata']);
            self::assertSame($options['metadata'], $instance->getMetadata());
            self::assertSame($options['metadata'], $instance->metadata);

            $instance = new Payout();
            $instance->metadata = $options['metadata'];
            self::assertSame($options['metadata'], $instance->getMetadata());
            self::assertSame($options['metadata'], $instance->metadata);
        }
    }

    public static function validDataProvider()
    {
        $result = [];
        $cancellationDetailsParties = PayoutCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = PayoutCancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);
        $payoutDestinationFactory = new PayoutDestinationFactory();
        $payoutDestinations = [
            PayoutDestinationType::YOO_MONEY => $payoutDestinationFactory->factoryFromArray([
                'type' => PaymentMethodType::YOO_MONEY,
                'account_number' => Random::str(11, 33, '1234567890'),
            ]),
            PayoutDestinationType::BANK_CARD => $payoutDestinationFactory->factoryFromArray([
                'type' => PaymentMethodType::BANK_CARD,
                'card' => [
                    'first6' => Random::str(6, '0123456789'),
                    'last4' => Random::str(4, '0123456789'),
                    'card_type' => Random::value(BankCardType::getValidValues()),
                    'issuer_country' => 'RU',
                    'issuer_name' => 'SberBank',
                ],
            ]),
            PayoutDestinationType::SBP => $payoutDestinationFactory->factoryFromArray([
                'type' => PaymentMethodType::SBP,
                'phone' => Random::str(4, 15, '0123456789'),
                'bank_id' => Random::str(4, 12, '0123456789'),
                'recipient_checked' => Random::bool(),
            ]),
        ];

        $result[] = [
            [
                'id' => Random::str(36, 50),
                'status' => Random::value(PayoutStatus::getValidValues()),
                'amount' => new MonetaryAmount(Random::int(1, 10000), 'RUB'),
                'description' => Random::str(1, Payout::MAX_LENGTH_DESCRIPTION),
                'payout_destination' => $payoutDestinations[Random::value(PayoutDestinationType::getValidValues())],
                'created_at' => date(YOOKASSA_DATE, Random::int(111111111, time())),
                'test' => true,
                'deal' => null,
                'self_employed' => null,
                'receipt' => new IncomeReceipt(),
                'metadata' => ['order_id' => '37'],
                'cancellation_details' => new PayoutCancellationDetails([
                    'party' => Random::value($cancellationDetailsParties),
                    'reason' => Random::value($cancellationDetailsReasons),
                ]),
            ],
        ];
        $result[] = [
            [
                'id' => Random::str(36, 50),
                'status' => Random::value(PayoutStatus::getValidValues()),
                'amount' => new MonetaryAmount(Random::int(1, 10000), 'RUB'),
                'description' => Random::str(1, Payout::MAX_LENGTH_DESCRIPTION),
                'payout_destination' => $payoutDestinations[Random::value(PayoutDestinationType::getValidValues())],
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'test' => true,
                'deal' => ['id' => Random::str(36, 50)],
                'receipt' => [
                    'service_name' => Random::str(1, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id' => Random::str(1, 50),
                    'url' => 'https://' . Random::str(1, 10, 'abcdefghijklmnopqrstuvwxyz') . '.ru',
                    'amount' => [
                        'value' => number_format(Random::float(1, 99), 2, '.', ''),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                ],
                'self_employed' => ['id' => Random::str(36, 50)],
                'metadata' => null,
                'cancellation_details' => null,
            ],
        ];

        for ($i = 0; $i < 20; $i++) {
            $payment = [
                'id' => Random::str(36, 50),
                'status' => Random::value(PayoutStatus::getValidValues()),
                'amount' => new MonetaryAmount(Random::int(1, 10000), 'RUB'),
                'description' => $i % 2 ? null : Random::str(Payout::MAX_LENGTH_DESCRIPTION),
                'payout_destination' => $payoutDestinations[Random::value([PaymentMethodType::YOO_MONEY, PaymentMethodType::BANK_CARD])],
                'created_at' => date(YOOKASSA_DATE, Random::int(111111111, time())),
                'test' => Random::bool(),
                'deal' => new PayoutDealInfo(['id' => Random::str(36, 50)]),
                'self_employed' => new PayoutSelfEmployed(['id' => Random::str(36, 50)]),
                'receipt' => new IncomeReceipt([
                    'service_name' => Random::str(2, IncomeReceipt::MAX_LENGTH_SERVICE_NAME),
                    'npd_receipt_id' => Random::str(2, 50),
                    'url' => 'https://' . Random::str(2, 10, 'abcdefghijklmnopqrstuvwxyz') . '.ru',
                    'amount' => [
                        'value' => number_format(Random::float(1, 99), 2, '.', ''),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                ]),
                'metadata' => new Metadata(),
                'cancellation_details' => new PayoutCancellationDetails([
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ]),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function invalidDataProvider(): array
    {
        $result = [
            [
                [
                    'id' => '',
                    'status' => '',
                    'amount' => '',
                    'payout_destination' => [],
                    'test' => '',
                    'deal' => new Metadata(),
                    'self_employed' => new stdClass(),
                    'receipt' => new Metadata(),
                    'metadata' => new stdClass(),
                    'created_at' => '',
                    'cancellation_details' => new stdClass(),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(51, 64)),
                'status' => Random::str(1, 35),
                'amount' => $i % 2 ? -1 : new stdClass(),
                'payout_destination' => $i % 2 ? [] : [new stdClass()],
                'test' => 0 === $i ? [] : new stdClass(),
                'deal' => 0 === $i ? Random::str(10) : new stdClass(),
                'self_employed' => 0 === $i ? Random::str(10) : new stdClass(),
                'receipt' => 0 === $i ? Random::str(10) : new stdClass(),
                'metadata' => 0 === $i ? Random::str(10) : new stdClass(),
                'cancellation_details' => $i % 2 ? Random::str(10) : -Random::int(),
                'created_at' => 0 === $i ? '23423-234-32' : -Random::int(),
            ];
            $result[] = [$payment];
        }

        return $result;
    }
}
