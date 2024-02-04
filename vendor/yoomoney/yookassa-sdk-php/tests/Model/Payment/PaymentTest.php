<?php

namespace Tests\YooKassa\Model\Payment;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\PaymentDealInfo;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\Metadata;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\AuthorizationDetails;
use YooKassa\Model\Payment\CancellationDetails;
use YooKassa\Model\Payment\CancellationDetailsPartyCode;
use YooKassa\Model\Payment\CancellationDetailsReasonCode;
use YooKassa\Model\Payment\Confirmation\ConfirmationRedirect;
use YooKassa\Model\Payment\Payment;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodQiwi;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Payment\Recipient;
use YooKassa\Model\Payment\Transfer;
use YooKassa\Model\Payment\TransferStatus;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Validator\Exceptions\ValidatorParameterException;

/**
 * @internal
 */
class PaymentTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new Payment();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new Payment();
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
        $instance = new Payment();
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
        $instance = new Payment();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetStatus(array $options): void
    {
        $instance = new Payment();

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new Payment();
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
        $instance = new Payment();
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
        $instance = new Payment();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetRecipient(array $options): void
    {
        $instance = new Payment();

        $instance->setRecipient($options['recipient']);
        self::assertSame($options['recipient'], $instance->getRecipient());
        self::assertSame($options['recipient'], $instance->recipient);

        $instance = new Payment();
        $instance->recipient = $options['recipient'];
        self::assertSame($options['recipient'], $instance->getRecipient());
        self::assertSame($options['recipient'], $instance->recipient);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidRecipient($value): void
    {
        $this->expectException(InvalidPropertyValueTypeException::class);
        $instance = new Payment();
        $instance->setRecipient($value['recipient']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidRecipient($value): void
    {
        $this->expectException(InvalidPropertyValueTypeException::class);
        $instance = new Payment();
        $instance->setRecipient($value['recipient']);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetAmount(array $options): void
    {
        $instance = new Payment();

        $instance->setAmount($options['amount']);
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);

        $instance = new Payment();
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
        $instance = new Payment();
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
        $instance = new Payment();
        $instance->amount = $value['amount'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetPaymentMethod(array $options): void
    {
        $instance = new Payment();

        $instance->setPaymentMethod($options['payment_method']);
        if (is_array($options['payment_method'])) {
            self::assertSame($options['payment_method'], $instance->getPaymentMethod()->toArray());
            self::assertSame($options['payment_method'], $instance->paymentMethod->toArray());
            self::assertSame($options['payment_method'], $instance->payment_method->toArray());
        } else {
            self::assertSame($options['payment_method'], $instance->getPaymentMethod());
            self::assertSame($options['payment_method'], $instance->paymentMethod);
            self::assertSame($options['payment_method'], $instance->payment_method);
        }

        $instance = new Payment();
        $instance->paymentMethod = $options['payment_method'];
        if (is_array($options['payment_method'])) {
            self::assertSame($options['payment_method'], $instance->getPaymentMethod()->toArray());
            self::assertSame($options['payment_method'], $instance->paymentMethod->toArray());
            self::assertSame($options['payment_method'], $instance->payment_method->toArray());
        } else {
            self::assertSame($options['payment_method'], $instance->getPaymentMethod());
            self::assertSame($options['payment_method'], $instance->paymentMethod);
            self::assertSame($options['payment_method'], $instance->payment_method);
        }

        $instance = new Payment();
        $instance->payment_method = $options['payment_method'];
        if (is_array($options['payment_method'])) {
            self::assertSame($options['payment_method'], $instance->getPaymentMethod()->toArray());
            self::assertSame($options['payment_method'], $instance->paymentMethod->toArray());
            self::assertSame($options['payment_method'], $instance->payment_method->toArray());
        } else {
            self::assertSame($options['payment_method'], $instance->getPaymentMethod());
            self::assertSame($options['payment_method'], $instance->paymentMethod);
            self::assertSame($options['payment_method'], $instance->payment_method);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentMethod($value): void
    {
        $this->expectException(InvalidPropertyValueTypeException::class);
        $instance = new Payment();
        $instance->setPaymentMethod($value['payment_method']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPaymentMethod($value): void
    {
        $this->expectException(InvalidPropertyValueTypeException::class);
        $instance = new Payment();
        $instance->paymentMethod = $value['payment_method'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakePaymentMethod($value): void
    {
        $this->expectException(InvalidPropertyValueTypeException::class);
        $instance = new Payment();
        $instance->payment_method = $value['payment_method'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCreatedAt(array $options): void
    {
        $instance = new Payment();

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new Payment();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new Payment();
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
        $instance = new Payment();
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
        $instance = new Payment();
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
        $instance = new Payment();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCapturedAt(array $options): void
    {
        $instance = new Payment();

        $instance->setCapturedAt($options['captured_at']);
        if (null === $options['captured_at'] || '' === $options['captured_at']) {
            self::assertNull($instance->getCapturedAt());
            self::assertNull($instance->capturedAt);
            self::assertNull($instance->captured_at);
        } else {
            self::assertSame($options['captured_at'], $instance->getCapturedAt()->format(YOOKASSA_DATE));
            self::assertSame($options['captured_at'], $instance->capturedAt->format(YOOKASSA_DATE));
            self::assertSame($options['captured_at'], $instance->captured_at->format(YOOKASSA_DATE));
        }

        $instance = new Payment();
        $instance->capturedAt = $options['captured_at'];
        if (null === $options['captured_at'] || '' === $options['captured_at']) {
            self::assertNull($instance->getCapturedAt());
            self::assertNull($instance->capturedAt);
            self::assertNull($instance->captured_at);
        } else {
            self::assertSame($options['captured_at'], $instance->getCapturedAt()->format(YOOKASSA_DATE));
            self::assertSame($options['captured_at'], $instance->capturedAt->format(YOOKASSA_DATE));
            self::assertSame($options['captured_at'], $instance->captured_at->format(YOOKASSA_DATE));
        }

        $instance = new Payment();
        $instance->captured_at = $options['captured_at'];
        if (null === $options['captured_at'] || '' === $options['captured_at']) {
            self::assertNull($instance->getCapturedAt());
            self::assertNull($instance->capturedAt);
            self::assertNull($instance->captured_at);
        } else {
            self::assertSame($options['captured_at'], $instance->getCapturedAt()->format(YOOKASSA_DATE));
            self::assertSame($options['captured_at'], $instance->capturedAt->format(YOOKASSA_DATE));
            self::assertSame($options['captured_at'], $instance->captured_at->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCapturedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->setCapturedAt($value['captured_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCapturedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->capturedAt = $value['captured_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeCapturedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->captured_at = $value['captured_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetConfirmation(array $options): void
    {
        $instance = new Payment();

        $instance->setConfirmation($options['confirmation']);
        self::assertSame($options['confirmation'], $instance->getConfirmation());
        self::assertSame($options['confirmation'], $instance->confirmation);

        $instance = new Payment();
        $instance->confirmation = $options['confirmation'];
        self::assertSame($options['confirmation'], $instance->getConfirmation());
        self::assertSame($options['confirmation'], $instance->confirmation);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidConfirmation($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $instance = new Payment();
        $instance->confirmation = $value['confirmation'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidConfirmation($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $instance = new Payment();
        $instance->confirmation = $value['confirmation'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetRefundedAmount(array $options): void
    {
        $instance = new Payment();

        $instance->setRefundedAmount($options['refunded_amount']);
        self::assertSame($options['refunded_amount'], $instance->getRefundedAmount());
        self::assertSame($options['refunded_amount'], $instance->refundedAmount);
        self::assertSame($options['refunded_amount'], $instance->refunded_amount);

        $instance = new Payment();
        $instance->refundedAmount = $options['refunded_amount'];
        self::assertSame($options['refunded_amount'], $instance->getRefundedAmount());
        self::assertSame($options['refunded_amount'], $instance->refundedAmount);
        self::assertSame($options['refunded_amount'], $instance->refunded_amount);

        $instance = new Payment();
        $instance->refunded_amount = $options['refunded_amount'];
        self::assertSame($options['refunded_amount'], $instance->getRefundedAmount());
        self::assertSame($options['refunded_amount'], $instance->refundedAmount);
        self::assertSame($options['refunded_amount'], $instance->refunded_amount);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidRefundedAmount($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $instance = new Payment();
        $instance->refundedAmount = $value['refunded_amount'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidRefundedAmount($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $instance = new Payment();
        $instance->refundedAmount = $value['refunded_amount'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeRefundedAmount($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $instance = new Payment();
        $instance->refundedAmount = $value['refunded_amount'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetPaid(array $options): void
    {
        $instance = new Payment();

        $instance->setPaid($options['paid']);
        self::assertSame($options['paid'], $instance->getPaid());
        self::assertSame($options['paid'], $instance->paid);

        $instance = new Payment();
        $instance->paid = $options['paid'];
        self::assertSame($options['paid'], $instance->getPaid());
        self::assertSame($options['paid'], $instance->paid);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetTest(array $options): void
    {
        $instance = new Payment();

        $instance->setTest($options['test']);
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);

        $instance = new Payment();
        $instance->test = $options['test'];
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetRefundable(array $options): void
    {
        $instance = new Payment();

        $instance->setRefundable($options['refundable']);
        self::assertSame($options['refundable'], $instance->getRefundable());
        self::assertSame($options['refundable'], $instance->refundable);

        $instance = new Payment();
        $instance->refundable = $options['refundable'];
        self::assertSame($options['refundable'], $instance->getRefundable());
        self::assertSame($options['refundable'], $instance->refundable);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetReceiptRegistration(array $options): void
    {
        $instance = new Payment();

        $instance->setReceiptRegistration($options['receipt_registration']);
        if (null === $options['receipt_registration'] || '' === $options['receipt_registration']) {
            self::assertNull($instance->getReceiptRegistration());
            self::assertNull($instance->receiptRegistration);
            self::assertNull($instance->receipt_registration);
        } else {
            self::assertSame($options['receipt_registration'], $instance->getReceiptRegistration());
            self::assertSame($options['receipt_registration'], $instance->receiptRegistration);
            self::assertSame($options['receipt_registration'], $instance->receipt_registration);
        }

        $instance = new Payment();
        $instance->receiptRegistration = $options['receipt_registration'];
        if (null === $options['receipt_registration'] || '' === $options['receipt_registration']) {
            self::assertNull($instance->getReceiptRegistration());
            self::assertNull($instance->receiptRegistration);
            self::assertNull($instance->receipt_registration);
        } else {
            self::assertSame($options['receipt_registration'], $instance->getReceiptRegistration());
            self::assertSame($options['receipt_registration'], $instance->receiptRegistration);
            self::assertSame($options['receipt_registration'], $instance->receipt_registration);
        }

        $instance = new Payment();
        $instance->receipt_registration = $options['receipt_registration'];
        if (null === $options['receipt_registration'] || '' === $options['receipt_registration']) {
            self::assertNull($instance->getReceiptRegistration());
            self::assertNull($instance->receiptRegistration);
            self::assertNull($instance->receipt_registration);
        } else {
            self::assertSame($options['receipt_registration'], $instance->getReceiptRegistration());
            self::assertSame($options['receipt_registration'], $instance->receiptRegistration);
            self::assertSame($options['receipt_registration'], $instance->receipt_registration);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceiptRegistration($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->setReceiptRegistration($value['receipt_registration']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidReceiptRegistration($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->receiptRegistration = $value['receipt_registration'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeReceiptRegistration($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->receipt_registration = $value['receipt_registration'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMetadata(array $options): void
    {
        $instance = new Payment();

        $instance->setMetadata($options['metadata']);
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);

        $instance = new Payment();
        $instance->metadata = $options['metadata'];
        self::assertSame($options['metadata'], $instance->getMetadata());
        self::assertSame($options['metadata'], $instance->metadata);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetAddTransfers(array $options): void
    {
        $instance = new Payment();

        self::assertEmpty($instance->getTransfers());
        self::assertEmpty($instance->transfers);

        $instance->setTransfers($options['transfers']);
        foreach ($options['transfers'] as $key => $transfer) {
            if (is_array($transfer)) {
                self::assertSame($transfer, $instance->getTransfers()->get($key)->toArray());
                self::assertSame($transfer, $instance->getTransfers()[$key]->toArray());
            } else {
                self::assertSame($transfer, $instance->getTransfers()->get($key));
                self::assertSame($transfer, $instance->getTransfers()[$key]);
            }
        }

        $instance = new Payment();
        $instance->transfers = $options['transfers'];
        foreach ($options['transfers'] as $key => $transfer) {
            if (is_array($transfer)) {
                self::assertSame($transfer, $instance->getTransfers()->get($key)->toArray());
                self::assertSame($transfer, $instance->getTransfers()[$key]->toArray());
            } else {
                self::assertSame($transfer, $instance->getTransfers()->get($key));
                self::assertSame($transfer, $instance->getTransfers()[$key]);
            }
        }
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidTransfers(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->setTransfers($options['transfers']);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetIncomeAmount(array $options): void
    {
        $instance = new Payment();
        $instance->setIncomeAmount($options['amount']);
        self::assertEquals($options['amount'], $instance->getIncomeAmount());
    }

    public static function validDataProvider()
    {
        $result = [];
        $cancellationDetailsParties = CancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = CancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);
        for ($i = 0; $i < 20; $i++) {
            $payment = [
                'id' => Random::str(36),
                'status' => Random::value(PaymentStatus::getValidValues()),
                'recipient' => new Recipient([
                    'account_id' => Random::str(2, 15),
                ]),
                'amount' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::RUB]),
                'description' => (0 === $i ? null : (1 === $i ? '' : (2 === $i ? Random::str(Payment::MAX_LENGTH_DESCRIPTION)
                    : Random::str(2, Payment::MAX_LENGTH_DESCRIPTION)))),
                'payment_method' => (0 === $i ? new PaymentMethodQiwi() : [
                    'type' => Random::value(PaymentMethodType::getValidValues()),
                    'id' => Random::str(10),
                    'saved' => Random::bool(),
                    'title' => Random::str(10),
                ]),
                'reference_id' => (0 === $i ? null : (1 === $i ? '' : Random::str(10, 20, 'abcdef0123456789'))),
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'captured_at' => (0 === $i ? null : date(YOOKASSA_DATE, Random::int(1, time()))),
                'expires_at' => (0 === $i ? null : date(YOOKASSA_DATE, Random::int(1, time()))),
                'confirmation' => new ConfirmationRedirect([
                    'confirmation_url' => 'https://test.com',
                ]),
                'charge' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::EUR]),
                'income' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::EUR]),
                'refunded_amount' => new ReceiptItemAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::EUR]),
                'paid' => Random::bool(),
                'test' => Random::bool(),
                'refundable' => Random::bool(),
                'receipt_registration' => 0 === $i ? null : Random::value(ReceiptRegistrationStatus::getValidValues()),
                'metadata' => new Metadata(),
                'cancellation_details' => new CancellationDetails([
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ]),
                'authorization_details' => (0 === $i ? null : new AuthorizationDetails([
                    'rrn' => Random::str(10),
                    'auth_code' => Random::str(10),
                    'three_d_secure' => ['applied' => Random::bool()],
                ])),
                'deal' => (0 === $i ? null : new PaymentDealInfo([
                    'id' => Random::str(36, 50),
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                            'amount' => [
                                'value' => round(Random::float(1.00, 100.00), 2),
                                'currency' => 'RUB',
                            ],
                        ],
                    ],
                ])),
                'transfers' => [
                    [
                        'account_id' => Random::str(36),
                        'amount' => ['value' => '123.00', 'currency' => CurrencyCode::EUR],
                        'status' => TransferStatus::PENDING,
                        'description' => Random::str(2, Transfer::MAX_LENGTH_DESCRIPTION),
                        'release_funds' => false
                    ],
                    new Transfer([
                        'account_id' => Random::str(36),
                        'amount' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::EUR]),
                        'platform_fee_amount' => new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => CurrencyCode::EUR]),
                        'description' => Random::str(2, Transfer::MAX_LENGTH_DESCRIPTION),
                    ]),
                ],
                'merchant_customer_id' => (0 === $i ? null : Random::str(2, Payment::MAX_LENGTH_MERCHANT_CUSTOMER_ID))
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function invalidDataProvider()
    {
        $result = [
            [
                [
                    'id' => '',
                    'status' => '',
                    'recipient' => new stdClass(),
                    'amount' => '',
                    'payment_method' => 1,
                    'reference_id' => [],
                    'confirmation' => 2,
                    'charge' => '',
                    'income' => '',
                    'refunded_amount' => 3,
                    'paid' => '',
                    'refundable' => '',
                    'created_at' => -Random::int(),
                    'captured_at' => '23423-234-234',
                    'receipt_registration' => Random::str(5),
                    'transfers' => Random::str(5),
                    'test' => '',
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(37, 64)),
                'status' => Random::str(2, 35),
                'recipient' => new stdClass(),
                'amount' => 'test',
                'payment_method' => 'test',
                'reference_id' => Random::str(66, 128),
                'confirmation' => 'test',
                'charge' => 'test',
                'income' => 'test',
                'refunded_amount' => 'test',
                'paid' => 0 === $i ? [] : new stdClass(),
                'refundable' => 0 === $i ? [] : new stdClass(),
                'created_at' => 0 === $i ? '23423-234-32' : -Random::int(),
                'captured_at' => -Random::int(),
                'receipt_registration' => 0 === $i ? true : Random::str(5),
                'transfers' => $i % 2 ? Random::str(2, 35) : new stdClass(),
                'test' => [],
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetExpiresAt(array $options): void
    {
        $instance = new Payment();

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

        $instance = new Payment();
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

        $instance = new Payment();
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
        $instance = new Payment();
        $instance->setExpiresAt($value['captured_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->expiresAt = $value['captured_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->expires_at = $value['captured_at'];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetDescription($options): void
    {
        $instance = new Payment();
        $instance->setDescription($options['description']);

        if (empty($options['description']) && ('0' !== $options['description'])) {
            self::assertEmpty($instance->getDescription());
        } else {
            self::assertEquals($options['description'], $instance->getDescription());
        }
    }

    public function testSetInvalidLengthDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $description = Random::str(Payment::MAX_LENGTH_DESCRIPTION + 1);
        $instance->setDescription($description);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetMerchantCustomerId($options): void
    {
        $instance = new Payment();
        $instance->setMerchantCustomerId($options['merchant_customer_id']);

        if (empty($options['merchant_customer_id'])) {
            self::assertEmpty($instance->getMerchantCustomerId());
        } else {
            self::assertEquals($options['merchant_customer_id'], $instance->getMerchantCustomerId());
        }
    }

    public function testSetInvalidLengthMerchantCustomerId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $merchant_customer_id = Random::str(Payment::MAX_LENGTH_MERCHANT_CUSTOMER_ID + 1);
        $instance->setMerchantCustomerId($merchant_customer_id);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetDeal(array $options): void
    {
        $instance = new Payment();

        $instance->setDeal($options['deal']);
        self::assertSame($options['deal'], $instance->getDeal());
        self::assertSame($options['deal'], $instance->deal);

        $instance = new Payment();
        $instance->deal = $options['deal'];
        self::assertSame($options['deal'], $instance->getDeal());
        self::assertSame($options['deal'], $instance->deal);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetAuthorizationDetails(array $options): void
    {
        $instance = new Payment();

        $instance->setAuthorizationDetails($options['authorization_details']);
        self::assertSame($options['authorization_details'], $instance->getAuthorizationDetails());
        self::assertSame($options['authorization_details'], $instance->authorizationDetails);
        self::assertSame($options['authorization_details'], $instance->authorization_details);

        $instance = new Payment();
        $instance->authorizationDetails = $options['authorization_details'];
        self::assertSame($options['authorization_details'], $instance->getAuthorizationDetails());
        self::assertSame($options['authorization_details'], $instance->authorizationDetails);
        self::assertSame($options['authorization_details'], $instance->authorization_details);

        $instance = new Payment();
        $instance->authorization_details = $options['authorization_details'];
        self::assertSame($options['authorization_details'], $instance->getAuthorizationDetails());
        self::assertSame($options['authorization_details'], $instance->authorizationDetails);
        self::assertSame($options['authorization_details'], $instance->authorization_details);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCancellationDetails(array $options): void
    {
        $instance = new Payment();

        $instance->setCancellationDetails($options['cancellation_details']);
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new Payment();
        $instance->cancellationDetails = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new Payment();
        $instance->cancellation_details = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);
    }
}
