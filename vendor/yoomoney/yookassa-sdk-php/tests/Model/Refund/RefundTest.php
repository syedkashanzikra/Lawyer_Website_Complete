<?php

namespace Tests\YooKassa\Model\Refund;

use DateTime;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\RefundDealInfo;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Refund\Refund;
use YooKassa\Model\Refund\RefundCancellationDetails;
use YooKassa\Model\Refund\RefundCancellationDetailsPartyCode;
use YooKassa\Model\Refund\RefundCancellationDetailsReasonCode;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Model\Refund\Source;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Validator\Exceptions\ValidatorParameterException;

/**
 * @internal
 */
class RefundTest extends TestCase
{
    /**
     * @dataProvider validIdDataProvider
     */
    public function testGetSetId(mixed $value): void
    {
        $instance = new Refund();

        $instance->setId($value);
        self::assertEquals((string) $value, $instance->getId());
        self::assertEquals((string) $value, $instance->id);

        $instance = new Refund();
        $instance->id = $value;
        self::assertEquals((string) $value, $instance->getId());
        self::assertEquals((string) $value, $instance->id);
    }

    public static function validIdDataProvider()
    {
        $values = 'abcdefghijklmnopqrstuvwxyz';
        $values .= strtoupper($values) . '0123456789._-+';

        return [
            [Random::str(36, $values)],
            [Random::str(36, $values)],
            [new StringObject(Random::str(36, $values))],
            [new StringObject(Random::str(36, $values))],
        ];
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->setId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->id = $value;
    }

    public static function invalidIdDataProvider(): array
    {
        return [
            [''],
            [Random::str(1, 35)],
            [Random::str(1)],
            [Random::str(35)],
            [Random::str(37, 48)],
            [Random::str(37)],
            [1],
            [0],
            [-1],
            [true],
            [false],
        ];
    }

    /**
     * @dataProvider validSources
     *
     * @param mixed $value
     */
    public function testSetSources($value): void
    {
        $instance = new Refund();
        $instance->setSources($value);
        if (is_array($value)) {
            $value = [new Source($value[0])];
        }
        self::assertEquals($value, $instance->getSources()->getItems()->toArray());
    }

    /**
     * @dataProvider invalidSourcesDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidSources($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->setSources($value);
    }

    /**
     * @return array[]
     *
     * @throws Exception
     */
    public static function validSources(): array
    {
        $sources = [];
        for ($i = 0; $i < 10; $i++) {
            $sources[$i][] = [
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
        $sources[$i][] = [new Source($sources[0])];

        return [$sources];
    }

    /**
     * @dataProvider validCancellationDetails
     *
     * @param mixed $value
     */
    public function testSetCancellationDetails($value): void
    {
        $instance = new Refund();
        $instance->setCancellationDetails($value);
        if (is_array($value)) {
            $value = new RefundCancellationDetails($value);
        }
        self::assertEquals($value, $instance->getCancellationDetails());
    }

    /**
     * @dataProvider invalidCancellationDetailsDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidCancellationDetails($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->setCancellationDetails($value);
    }

    /**
     * @return array[]
     *
     * @throws Exception
     */
    public static function validCancellationDetails(): array
    {
        $cancellationDetailsParties = RefundCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = RefundCancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);

        $cancellation_details = [];
        for ($i = 0; $i < 10; $i++) {
            $cancellation_details[] = [
                [
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ]
            ];
        }

        return $cancellation_details;
    }

    /**
     * @dataProvider validIdDataProvider
     */
    public function testGetSetPaymentId(mixed $value): void
    {
        $instance = new Refund();

        $instance->setPaymentId($value);
        self::assertEquals((string) $value, $instance->getPaymentId());
        self::assertEquals((string) $value, $instance->paymentId);
        self::assertEquals((string) $value, $instance->payment_id);

        $instance = new Refund();
        $instance->paymentId = $value;
        self::assertEquals((string) $value, $instance->getPaymentId());
        self::assertEquals((string) $value, $instance->paymentId);
        self::assertEquals((string) $value, $instance->payment_id);

        $instance = new Refund();
        $instance->payment_id = $value;
        self::assertEquals((string) $value, $instance->getPaymentId());
        self::assertEquals((string) $value, $instance->paymentId);
        self::assertEquals((string) $value, $instance->payment_id);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->setPaymentId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->paymentId = $value;
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakePaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->payment_id = $value;
    }

    /**
     * @dataProvider validStatusDataProvider
     */
    public function testGetSetStatus(mixed $value): void
    {
        $instance = new Refund();

        $instance->setStatus($value);
        self::assertEquals((string) $value, $instance->getStatus());
        self::assertEquals((string) $value, $instance->status);

        $instance = new Refund();
        $instance->status = $value;
        self::assertEquals((string) $value, $instance->getStatus());
        self::assertEquals((string) $value, $instance->status);
    }

    public static function validStatusDataProvider(): array
    {
        $result = [];
        foreach (RefundStatus::getValidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->setStatus($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->status = $value;
    }

    /**
     * @dataProvider validCreatedAtDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCreatedAt($value): void
    {
        $instance = new Refund();

        if (is_numeric($value)) {
            $expected = $value;
        } elseif ($value instanceof DateTime) {
            $expected = $value->getTimestamp();
        } else {
            $expected = strtotime((string) $value);
        }

        $instance->setCreatedAt($value);
        self::assertSame($expected, $instance->getCreatedAt()->getTimestamp());
        self::assertSame($expected, $instance->createdAt->getTimestamp());
        self::assertSame($expected, $instance->created_at->getTimestamp());

        $instance = new Refund();
        $instance->createdAt = $value;
        self::assertSame($expected, $instance->getCreatedAt()->getTimestamp());
        self::assertSame($expected, $instance->createdAt->getTimestamp());
        self::assertSame($expected, $instance->created_at->getTimestamp());

        $instance = new Refund();
        $instance->created_at = $value;
        self::assertSame($expected, $instance->getCreatedAt()->getTimestamp());
        self::assertSame($expected, $instance->createdAt->getTimestamp());
        self::assertSame($expected, $instance->created_at->getTimestamp());
    }

    public static function validCreatedAtDataProvider(): array
    {
        return [
            [new DateTime()],
            [new DateTime(date(YOOKASSA_DATE, Random::int(1, time())))],
            [date(YOOKASSA_DATE)],
            [date(YOOKASSA_DATE, Random::int(1, time()))],
            [new StringObject(date(YOOKASSA_DATE))],
            [new StringObject(date(YOOKASSA_DATE, Random::int(1, time())))],
        ];
    }

    /**
     * @dataProvider invalidCreatedAtDataProvider
     *
     * @param $value
     * @param $exception
     * @return void
     */
    public function testSetInvalidCreatedAt($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->setCreatedAt($value);
    }

    /**
     * @dataProvider invalidCreatedAtDataProvider
     *
     * @param mixed $value
     * @param $exception
     */
    public function testSetterInvalidCreatedAt($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->createdAt = $value;
    }

    /**
     * @dataProvider invalidCreatedAtDataProvider
     *
     * @param mixed $value
     * @param $exception
     */
    public function testSetterInvalidSnakeCreatedAt($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->created_at = $value;
    }

    public static function invalidCreatedAtDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [true, InvalidPropertyValueException::class],
            [false, EmptyPropertyValueException::class],
            [[], TypeError::class],
            [fopen(__FILE__, 'rb'), TypeError::class],
        ];

    }

    /**
     * @dataProvider validAmountDataProvider
     */
    public function testGetSetAmount(AmountInterface $value): void
    {
        $instance = new Refund();

        $instance->setAmount($value);
        self::assertSame($value, $instance->getAmount());
        self::assertSame($value, $instance->amount);

        $instance = new Refund();
        $instance->amount = $value;
        self::assertSame($value, $instance->getAmount());
        self::assertSame($value, $instance->amount);
    }

    public static function validAmountDataProvider(): array
    {
        return [
            [new MonetaryAmount(1)],
            [new MonetaryAmount(Random::float(0.01, 9999999.99))],
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
        $instance = new Refund();
        $instance->setAmount($value);
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->amount = $value;
    }

    public static function invalidAmountDataProvider(): array
    {
        return [
            ['string'],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider validReceiptRegisteredDataProvider
     */
    public function testGetSetReceiptRegistered(mixed $value): void
    {
        $instance = new Refund();

        $instance->setReceiptRegistration($value);
        self::assertEquals((string) $value, $instance->getReceiptRegistration());
        self::assertEquals((string) $value, $instance->receiptRegistration);
        self::assertEquals((string) $value, $instance->receipt_registration);

        $instance = new Refund();
        $instance->receiptRegistration = $value;
        self::assertEquals((string) $value, $instance->getReceiptRegistration());
        self::assertEquals((string) $value, $instance->receiptRegistration);
        self::assertEquals((string) $value, $instance->receipt_registration);

        $instance = new Refund();
        $instance->receipt_registration = $value;
        self::assertEquals((string) $value, $instance->getReceiptRegistration());
        self::assertEquals((string) $value, $instance->receiptRegistration);
        self::assertEquals((string) $value, $instance->receipt_registration);
    }

    public static function validReceiptRegisteredDataProvider(): array
    {
        $result = [];
        foreach (ReceiptRegistrationStatus::getValidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * @dataProvider invalidReceiptRegisteredDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceiptRegistered($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->setReceiptRegistration($value);
    }

    /**
     * @dataProvider invalidReceiptRegisteredDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidReceiptRegistration($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->receiptRegistration = $value;
    }

    /**
     * @dataProvider invalidReceiptRegisteredDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeReceiptRegistration($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->receipt_registration = $value;
    }

    public static function invalidReceiptRegisteredDataProvider(): array
    {
        return [
            ['invalid'],
            [true],
            [false],
            [Random::str(1, 10)],
            [new StringObject(Random::str(1, 10))],
        ];
    }

    /**
     * @dataProvider validDescriptionDataProvider
     */
    public function testGetSetDescription(mixed $value): void
    {
        $instance = new Refund();

        $instance->setDescription($value);
        self::assertEquals((string) $value, $instance->getDescription());
        self::assertEquals((string) $value, $instance->description);

        $instance = new Refund();
        $instance->description = $value;
        self::assertEquals((string) $value, $instance->getDescription());
        self::assertEquals((string) $value, $instance->description);
    }

    public static function validDescriptionDataProvider()
    {
        return [
            [Random::str(1, 249)],
            [new StringObject(Random::str(1, 249))],
            [Random::str(250)],
            [new StringObject(Random::str(250))],
        ];
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDescription($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->setDescription($value);
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidDescription($value, $exception): void
    {
        $this->expectException($exception);
        $instance = new Refund();
        $instance->description = $value;
    }

    public static function invalidDescriptionDataProvider()
    {
        return [
            [Random::str(Refund::MAX_LENGTH_DESCRIPTION + 1), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [fopen(__FILE__, 'r'), TypeError::class],
        ];
    }

    public static function invalidSourcesDataProvider()
    {
        return [
            [Random::str(Refund::MAX_LENGTH_DESCRIPTION + 1), ValidatorParameterException::class],
            [fopen(__FILE__, 'r'), ValidatorParameterException::class],
        ];
    }

    public static function invalidCancellationDetailsDataProvider()
    {
        return [
            [Random::str(Refund::MAX_LENGTH_DESCRIPTION + 1), ValidatorParameterException::class],
            [fopen(__FILE__, 'r'), ValidatorParameterException::class],
        ];
    }

    /**
     * @dataProvider validDealDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetDeal($value): void
    {
        $instance = new Refund();

        $instance->setDeal($value);
        self::assertSame($value, $instance->getDeal());
        self::assertSame($value, $instance->deal);

        $instance = new Refund();
        $instance->deal = $value;
        self::assertSame($value, $instance->getDeal());
        self::assertSame($value, $instance->deal);
    }

    /**
     * @throws Exception
     */
    public static function validDealDataProvider(): array
    {
        return [
            [null],
            [new RefundDealInfo(['id' => Random::str(36), 'amount' => 1, 'refund_settlements' => self::generateRefundSettlements()])],
            [new RefundDealInfo(['id' => Random::str(36), 'amount' => Random::float(0.01, 9999999.99), 'refund_settlements' => self::generateRefundSettlements()])],
        ];
    }

    private static function generateRefundSettlements(): array
    {
        $return = [];
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = self::generateRefundSettlement();
        }

        return $return;
    }

    private static function generateRefundSettlement(): array
    {
        return [
            'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
            'amount' => [
                'value' => round(Random::float(1.00, 100.00), 2),
                'currency' => 'RUB',
            ],
        ];
    }

    /**
     * @dataProvider invalidDealDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDeal($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->setDeal($value);
    }

    /**
     * @dataProvider invalidDealDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidDeal($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Refund();
        $instance->deal = $value;
    }

    public static function invalidDealDataProvider(): array
    {
        return [
            [true],
            [false],
            [new MonetaryAmount()],
            [1],
            [new stdClass()],
        ];
    }
}
