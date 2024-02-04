<?php

namespace Tests\YooKassa\Request\Payouts;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\PayoutDealInfo;
use YooKassa\Model\Metadata;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\Payout;
use YooKassa\Request\Payouts\CreatePayoutRequest;
use YooKassa\Request\Payouts\CreatePayoutRequestBuilder;
use YooKassa\Request\Payouts\IncomeReceiptData;
use YooKassa\Request\Payouts\PayoutDestinationData\PayoutDestinationDataBankCard;
use YooKassa\Request\Payouts\PayoutPersonalData;
use YooKassa\Request\Payouts\PayoutSelfEmployedInfo;

/**
 * @internal
 */
class CreatePayoutRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testAmount($options): void
    {
        $instance = new CreatePayoutRequest();

        $instance->setAmount($options['amount']);

        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmountToken($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setAmount($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPayoutToken($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasPayoutToken());
        self::assertNull($instance->getPayoutToken());
        self::assertNull($instance->payoutToken);
        self::assertNull($instance->payout_token);

        $instance->setPayoutToken($options['payoutToken']);
        if (empty($options['payoutToken'])) {
            self::assertFalse($instance->hasPayoutToken());
            self::assertNull($instance->getPayoutToken());
            self::assertNull($instance->payoutToken);
            self::assertNull($instance->payout_token);
        } else {
            self::assertTrue($instance->hasPayoutToken());
            self::assertSame($options['payoutToken'], $instance->getPayoutToken());
            self::assertSame($options['payoutToken'], $instance->payoutToken);
            self::assertSame($options['payoutToken'], $instance->payout_token);
        }

        $instance->setPayoutToken(null);
        self::assertFalse($instance->hasPayoutToken());
        self::assertNull($instance->getPayoutToken());
        self::assertNull($instance->payoutToken);

        $instance->payoutToken = $options['payoutToken'];
        if (empty($options['payoutToken'])) {
            self::assertFalse($instance->hasPayoutToken());
            self::assertNull($instance->getPayoutToken());
            self::assertNull($instance->payoutToken);
            self::assertNull($instance->payout_token);
        } else {
            self::assertTrue($instance->hasPayoutToken());
            self::assertSame($options['payoutToken'], $instance->getPayoutToken());
            self::assertSame($options['payoutToken'], $instance->payoutToken);
            self::assertSame($options['payoutToken'], $instance->payout_token);
        }

        $instance->payoutToken = null;
        self::assertFalse($instance->hasPayoutToken());
        self::assertNull($instance->getPayoutToken());
        self::assertNull($instance->payoutToken);

        $instance->payout_token = $options['payoutToken'];
        if (empty($options['payoutToken'])) {
            self::assertFalse($instance->hasPayoutToken());
            self::assertNull($instance->getPayoutToken());
            self::assertNull($instance->payoutToken);
            self::assertNull($instance->payout_token);
        } else {
            self::assertTrue($instance->hasPayoutToken());
            self::assertSame($options['payoutToken'], $instance->getPayoutToken());
            self::assertSame($options['payoutToken'], $instance->payoutToken);
            self::assertSame($options['payoutToken'], $instance->payout_token);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPaymentMethodId($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);
        self::assertNull($instance->payment_method_id);

        $instance->setPaymentMethodId($options['payment_method_id']);
        if (empty($options['payment_method_id'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
            self::assertNull($instance->payment_method_id);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['payment_method_id'], $instance->getPaymentMethodId());
            self::assertSame($options['payment_method_id'], $instance->paymentMethodId);
            self::assertSame($options['payment_method_id'], $instance->payment_method_id);
        }

        $instance->setPaymentMethodId(null);
        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);

        $instance->paymentMethodId = $options['payment_method_id'];
        if (empty($options['payment_method_id'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
            self::assertNull($instance->payment_method_id);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['payment_method_id'], $instance->getPaymentMethodId());
            self::assertSame($options['payment_method_id'], $instance->paymentMethodId);
            self::assertSame($options['payment_method_id'], $instance->payment_method_id);
        }

        $instance->paymentMethodId = null;
        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);

        $instance->payment_method_id = $options['payment_method_id'];
        if (empty($options['payment_method_id'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
            self::assertNull($instance->payment_method_id);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['payment_method_id'], $instance->getPaymentMethodId());
            self::assertSame($options['payment_method_id'], $instance->paymentMethodId);
            self::assertSame($options['payment_method_id'], $instance->payment_method_id);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPayoutDestinationData($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasPayoutDestinationData());
        self::assertNull($instance->getPayoutDestinationData());
        self::assertNull($instance->payoutDestinationData);
        self::assertNull($instance->payout_destination_data);

        $instance->setPayoutDestinationData($options['payoutDestinationData']);
        if (empty($options['payoutDestinationData'])) {
            self::assertFalse($instance->hasPayoutDestinationData());
            self::assertNull($instance->getPayoutDestinationData());
            self::assertNull($instance->payoutDestinationData);
            self::assertNull($instance->payout_destination_data);
        } else {
            self::assertTrue($instance->hasPayoutDestinationData());
            self::assertEquals($options['payoutDestinationData'], $instance->getPayoutDestinationData()->toArray());
            self::assertEquals($options['payoutDestinationData'], $instance->payoutDestinationData->toArray());
            self::assertEquals($options['payoutDestinationData'], $instance->payout_destination_data->toArray());
        }

        $instance->setPayoutDestinationData(null);
        self::assertFalse($instance->hasPayoutDestinationData());
        self::assertNull($instance->getPayoutDestinationData());
        self::assertNull($instance->payoutDestinationData);
        self::assertNull($instance->payout_destination_data);

        $instance->payoutDestinationData = $options['payoutDestinationData'];
        if (empty($options['payoutDestinationData'])) {
            self::assertFalse($instance->hasPayoutDestinationData());
            self::assertNull($instance->getPayoutDestinationData());
            self::assertNull($instance->payoutDestinationData);
            self::assertNull($instance->payout_destination_data);
        } else {
            self::assertTrue($instance->hasPayoutDestinationData());
            self::assertEquals($options['payoutDestinationData'], $instance->getPayoutDestinationData()->toArray());
            self::assertEquals($options['payoutDestinationData'], $instance->payoutDestinationData->toArray());
            self::assertEquals($options['payoutDestinationData'], $instance->payout_destination_data->toArray());
        }
    }

    /**
     * @dataProvider invalidPayoutDestinationDataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPayoutDestinationData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setPayoutDestinationData($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testDescription($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasDescription());
        self::assertNull($instance->getDescription());
        self::assertNull($instance->description);

        $expected = $options['description'];

        $instance->setDescription($options['description']);
        if (empty($options['description'])) {
            self::assertFalse($instance->hasDescription());
            self::assertNull($instance->getDescription());
            self::assertNull($instance->description);
        } else {
            self::assertTrue($instance->hasDescription());
            self::assertSame($expected, $instance->getDescription());
            self::assertSame($expected, $instance->description);
        }

        $instance->setDescription(null);
        self::assertFalse($instance->hasDescription());
        self::assertNull($instance->getDescription());
        self::assertNull($instance->description);

        $instance->description = $options['description'];
        if (empty($options['description'])) {
            self::assertFalse($instance->hasDescription());
            self::assertNull($instance->getDescription());
            self::assertNull($instance->description);
        } else {
            self::assertTrue($instance->hasDescription());
            self::assertSame($expected, $instance->getDescription());
            self::assertSame($expected, $instance->description);
        }
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDescription($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setDescription($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testDeal($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $expected = $options['deal'];
        if ($expected instanceof PayoutDealInfo) {
            $expected = $expected->toArray();
        }

        $instance->setDeal($options['deal']);
        if (empty($options['deal'])) {
            self::assertFalse($instance->hasDeal());
            self::assertNull($instance->getDeal());
            self::assertNull($instance->deal);
        } else {
            self::assertTrue($instance->hasDeal());
            self::assertSame($expected, $instance->getDeal()->toArray());
            self::assertSame($expected, $instance->deal->toArray());
        }

        $instance->setDeal(null);
        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $instance->deal = $options['deal'];
        if (empty($options['deal'])) {
            self::assertFalse($instance->hasDeal());
            self::assertNull($instance->getDeal());
            self::assertNull($instance->deal);
        } else {
            self::assertTrue($instance->hasDeal());
            self::assertSame($expected, $instance->getDeal()->toArray());
            self::assertSame($expected, $instance->deal->toArray());
        }
    }

    /**
     * @dataProvider invalidDealDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDeal($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setDeal($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSelfEmployed($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasSelfEmployed());
        self::assertNull($instance->getSelfEmployed());
        self::assertNull($instance->self_employed);
        self::assertNull($instance->selfEmployed);

        $expected = $options['self_employed'];
        if ($expected instanceof PayoutSelfEmployedInfo) {
            $expected = $expected->toArray();
        }

        $instance->setSelfEmployed($options['self_employed']);
        if (empty($options['self_employed'])) {
            self::assertFalse($instance->hasSelfEmployed());
            self::assertNull($instance->getSelfEmployed());
            self::assertNull($instance->self_employed);
            self::assertNull($instance->selfEmployed);
        } else {
            self::assertTrue($instance->hasSelfEmployed());
            self::assertSame($expected, $instance->getSelfEmployed()->toArray());
            self::assertSame($expected, $instance->self_employed->toArray());
            self::assertSame($expected, $instance->selfEmployed->toArray());
        }

        $instance->setSelfEmployed(null);
        self::assertFalse($instance->hasSelfEmployed());
        self::assertNull($instance->getSelfEmployed());
        self::assertNull($instance->self_employed);
        self::assertNull($instance->selfEmployed);

        $instance->self_employed = $options['self_employed'];
        if (empty($options['self_employed'])) {
            self::assertFalse($instance->hasSelfEmployed());
            self::assertNull($instance->getSelfEmployed());
            self::assertNull($instance->self_employed);
            self::assertNull($instance->selfEmployed);
        } else {
            self::assertTrue($instance->hasSelfEmployed());
            self::assertSame($expected, $instance->getSelfEmployed()->toArray());
            self::assertSame($expected, $instance->self_employed->toArray());
            self::assertSame($expected, $instance->selfEmployed->toArray());
        }
    }

    /**
     * @dataProvider invalidSelfEmployedDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidSelfEmployed($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setSelfEmployed($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testReceiptData($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasReceiptData());
        self::assertNull($instance->getReceiptData());
        self::assertNull($instance->receipt_data);
        self::assertNull($instance->receiptData);

        $expected = $options['receipt_data'];
        if ($expected instanceof IncomeReceiptData) {
            $expected = $expected->toArray();
        }

        $instance->setReceiptData($options['receipt_data']);
        if (empty($options['receipt_data'])) {
            self::assertFalse($instance->hasReceiptData());
            self::assertNull($instance->getReceiptData());
            self::assertNull($instance->receipt_data);
            self::assertNull($instance->receiptData);
        } else {
            self::assertTrue($instance->hasReceiptData());
            self::assertSame($expected, $instance->getReceiptData()->toArray());
            self::assertSame($expected, $instance->receipt_data->toArray());
            self::assertSame($expected, $instance->receiptData->toArray());
        }

        $instance->setReceiptData(null);
        self::assertFalse($instance->hasReceiptData());
        self::assertNull($instance->getReceiptData());
        self::assertNull($instance->receipt_data);
        self::assertNull($instance->receiptData);

        $instance->receipt_data = $options['receipt_data'];
        if (empty($options['receipt_data'])) {
            self::assertFalse($instance->hasReceiptData());
            self::assertNull($instance->getReceiptData());
            self::assertNull($instance->receipt_data);
            self::assertNull($instance->receiptData);
        } else {
            self::assertTrue($instance->hasReceiptData());
            self::assertSame($expected, $instance->getReceiptData()->toArray());
            self::assertSame($expected, $instance->receipt_data->toArray());
            self::assertSame($expected, $instance->receiptData->toArray());
        }
    }

    /**
     * @dataProvider invalidReceiptDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceiptData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setReceiptData($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPersonalData($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasPersonalData());
        self::assertEmpty($instance->getPersonalData());
        self::assertEmpty($instance->personal_data);
        self::assertNull($instance->receiptData);

        $instance->setPersonalData($options['personal_data']);
        if (empty($options['personal_data'])) {
            self::assertFalse($instance->hasPersonalData());
            self::assertEmpty($instance->getPersonalData());
            self::assertEmpty($instance->personal_data);
            self::assertNull($instance->receiptData);
        } else {
            self::assertTrue($instance->hasPersonalData());
            foreach ($options['personal_data'] as $key => $expected) {
                if ($expected instanceof PayoutPersonalData) {
                    $expected = $expected->toArray();
                }
                $array = $instance->getPersonalData();
                self::assertSame($expected, $array[$key]->toArray());
                $array = $instance->personal_data;
                self::assertSame($expected, $array[$key]->toArray());
                $array = $instance->personalData;
                self::assertSame($expected, $array[$key]->toArray());
            }
        }

        $instance->setPersonalData(null);
        self::assertFalse($instance->hasPersonalData());
        self::assertEmpty($instance->getPersonalData());
        self::assertEmpty($instance->personal_data);
        self::assertEmpty($instance->personalData);

        $instance->personal_data = $options['personal_data'];
        if (empty($options['personal_data'])) {
            self::assertFalse($instance->hasPersonalData());
            self::assertEmpty($instance->getPersonalData());
            self::assertEmpty($instance->personal_data);
            self::assertEmpty($instance->personalData);
        } else {
            self::assertTrue($instance->hasPersonalData());
            foreach ($options['personal_data'] as $key => $expected) {
                if ($expected instanceof PayoutPersonalData) {
                    $expected = $expected->toArray();
                }
                $array = $instance->getPersonalData();
                self::assertSame($expected, $array[$key]->toArray());
                $array = $instance->personal_data;
                self::assertSame($expected, $array[$key]->toArray());
                $array = $instance->personalData;
                self::assertSame($expected, $array[$key]->toArray());
            }
        }
    }

    /**
     * @dataProvider invalidPersonalDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPersonalData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setPersonalData($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testMetadata($options): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $expected = $options['metadata'];
        if ($expected instanceof Metadata) {
            $expected = $expected->toArray();
        }

        $instance->setMetadata($options['metadata']);
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }

        $instance->setMetadata(null);
        self::assertFalse($instance->hasMetadata());
        self::assertNull($instance->getMetadata());
        self::assertNull($instance->metadata);

        $instance->metadata = $options['metadata'];
        if (empty($options['metadata'])) {
            self::assertFalse($instance->hasMetadata());
            self::assertNull($instance->getMetadata());
            self::assertNull($instance->metadata);
        } else {
            self::assertTrue($instance->hasMetadata());
            self::assertSame($expected, $instance->getMetadata()->toArray());
            self::assertSame($expected, $instance->metadata->toArray());
        }
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMetadata($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePayoutRequest();
        $instance->setMetadata($value);
    }

    public function testValidate(): void
    {
        $instance = new CreatePayoutRequest();

        self::assertFalse($instance->validate());

        $amount = new MonetaryAmount(1);
        $instance->setAmount($amount);
        self::assertFalse($instance->validate());

        $instance->setAmount(new MonetaryAmount(10));
        self::assertFalse($instance->validate());

        $instance->setPayoutToken(null);
        self::assertFalse($instance->validate());
        $instance->setDescription('test');
        self::assertFalse($instance->validate());
        $instance->setDeal(new PayoutDealInfo(['id' => Random::str(36, 50)]));
        self::assertFalse($instance->validate());
        $instance->setAmount(new MonetaryAmount(1));
        self::assertFalse($instance->validate());

        $instance->setPayoutToken(Random::str(10));
        $instance->setPaymentMethodId('test');
        self::assertFalse($instance->validate());
        $instance->setPersonalData([new PayoutPersonalData(['id' => Random::str(36, 50)])]);
        self::assertFalse($instance->validate());
        $instance->setReceiptData(new IncomeReceiptData(['service_name' => Random::str(1, 50)]));
        self::assertFalse($instance->validate());
        $instance->setSelfEmployed(new PayoutSelfEmployedInfo(['id' => Random::str(36, 50)]));
        self::assertFalse($instance->validate());

        $instance->setPayoutToken(Random::str(10));
        $instance->setPayoutDestinationData(new PayoutDestinationDataBankCard());
        self::assertFalse($instance->validate());
        $instance->setPayoutToken(null);
        $instance->setPaymentMethodId(null);
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = CreatePayoutRequest::builder();
        self::assertInstanceOf(CreatePayoutRequestBuilder::class, $builder);
    }

    public static function validDataProvider(): array
    {
        $metadata = new Metadata();
        $metadata->test = 'test';
        $result = [
            [
                [
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'payoutToken' => null,
                    'payoutDestinationData' => null,
                    'metadata' => null,
                    'description' => null,
                    'deal' => null,
                    'payment_method_id' => null,
                    'self_employed' => null,
                    'receipt_data' => null,
                    'personal_data' => null,
                ],
            ],
            [
                [
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'payoutToken' => '',
                    'payoutDestinationData' => Random::value(self::payoutDestinationData()),
                    'metadata' => [new Metadata()],
                    'description' => '',
                    'deal' => new PayoutDealInfo(['id' => Random::str(36, 50)]),
                    'payment_method_id' => '',
                    'self_employed' => new PayoutSelfEmployedInfo(['id' => Random::str(36, 50)]),
                    'receipt_data' => new IncomeReceiptData(['service_name' => Random::str(1, 50)]),
                    'personal_data' => [new PayoutPersonalData(['id' => Random::str(36, 50)])],
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                'payoutToken' => uniqid('', true),
                'payoutDestinationData' => Random::value(self::payoutDestinationData()),
                'metadata' => ($i % 2) ? $metadata : ['test' => 'test'],
                'description' => Random::str(5, 128),
                'deal' => ($i % 2) ? new PayoutDealInfo(['id' => Random::str(36, 50)]) : ['id' => Random::str(36, 50)],
                'payment_method_id' => Random::str(5, 128),
                'self_employed' => ($i % 2) ? new PayoutSelfEmployedInfo(['id' => Random::str(36, 50)]) : ['id' => Random::str(36, 50)],
                'receipt_data' => ($i % 2) ? new IncomeReceiptData(['service_name' => Random::str(36, 50), 'amount' => new MonetaryAmount(Random::int(1, 1000000))]) : ['service_name' => Random::str(36, 50), 'amount' => ['value' => Random::int(1, 1000000) . '.00', 'currency' => CurrencyCode::RUB]],
                'personal_data' => [($i % 2) ? new PayoutPersonalData(['id' => Random::str(36, 50)]) : ['id' => Random::str(36, 50)]],
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function payoutDestinationData(): array
    {
        return [
            [
                'type' => PaymentMethodType::YOO_MONEY,
                'account_number' => Random::str(11, 33, '0123456789'),
            ],
            [
                'type' => PaymentMethodType::BANK_CARD,
                'card' => [
                    'number' => Random::str(16, 16, '0123456789'),
                ],
            ],
            [
                'type' => PaymentMethodType::SBP,
                'phone' => Random::str(4, 15, '0123456789'),
                'bank_id' => Random::str(4, 12, '0123456789'),
            ],
        ];
    }

    public static function invalidAmountDataProvider(): array
    {
        return [
            [null],
            [''],
            [false],
            [true],
            [new stdClass()],
        ];
    }

    public static function invalidPayoutDestinationDataDataProvider(): array
    {
        return [
            [[]],
            [false],
            [true],
            [1],
            [Random::str(10)],
            [new stdClass()],
        ];
    }

    public static function invalidMetadataDataProvider(): array
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
        ];
    }

    public static function invalidDescriptionDataProvider(): array
    {
        return [
            [Random::str(Payout::MAX_LENGTH_DESCRIPTION + 1)],
        ];
    }

    public static function invalidDealDataProvider(): array
    {
        return [
            [false],
            [true],
            [new stdClass()],
            [Random::str(10)],
        ];
    }


    public static function invalidSelfEmployedDataProvider(): array
    {
        return [
            [false],
            [true],
            [new stdClass()],
            [Random::str(10)],
        ];
    }

    public static function invalidReceiptDataProvider(): array
    {
        return [
            [false],
            [true],
            [new stdClass()],
            [Random::str(10)],
        ];
    }

    public static function invalidPersonalDataProvider(): array
    {
        return [
            [new stdClass()],
            [Random::str(10)],
        ];
    }
}
