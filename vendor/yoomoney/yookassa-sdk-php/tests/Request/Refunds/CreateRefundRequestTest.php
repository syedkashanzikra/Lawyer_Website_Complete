<?php

namespace Tests\YooKassa\Request\Refunds;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\RefundDealData;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\Receipt;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Request\Refunds\CreateRefundRequest;
use YooKassa\Request\Refunds\CreateRefundRequestBuilder;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Validator\Exceptions\ValidatorParameterException;

/**
 * @internal
 */
class CreateRefundRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPaymentId($options): void
    {
        $instance = new CreateRefundRequest();

        $instance->setPaymentId($options['paymentId']);

        self::assertEquals($options['paymentId'], $instance->getPaymentId());
        self::assertEquals($options['paymentId'], $instance->paymentId);

        $instance = new CreateRefundRequest();

        $instance->paymentId = $options['paymentId'];

        self::assertEquals($options['paymentId'], $instance->getPaymentId());
        self::assertEquals($options['paymentId'], $instance->paymentId);
    }

    /**
     * @dataProvider invalidPaymentIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateRefundRequest();
        $instance->setPaymentId($value);
    }

    /**
     * @dataProvider invalidPaymentIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPaymentId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateRefundRequest();
        $instance->paymentId = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testAmount($options): void
    {
        $instance = new CreateRefundRequest();

        $instance->setAmount($options['amount']);

        self::assertEquals($options['amount']->getValue(), $instance->getAmount()->getValue());
        self::assertEquals($options['amount']->getValue(), $instance->amount->getValue());
        self::assertEquals($options['amount']->getCurrency(), $instance->getAmount()->getCurrency());
        self::assertEquals($options['amount']->getCurrency(), $instance->amount->getCurrency());

        $instance = new CreateRefundRequest();

        $instance->amount = $options['amount'];

        self::assertEquals($options['amount']->getValue(), $instance->getAmount()->getValue());
        self::assertEquals($options['amount']->getValue(), $instance->amount->getValue());
        self::assertEquals($options['amount']->getCurrency(), $instance->getAmount()->getCurrency());
        self::assertEquals($options['amount']->getCurrency(), $instance->amount->getCurrency());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testDescription($options): void
    {
        $instance = new CreateRefundRequest();

        self::assertFalse($instance->hasDescription());
        self::assertNull($instance->getDescription());
        self::assertNull($instance->description);

        $instance->setDescription($options['description']);
        if (empty($options['description'])) {
            self::assertFalse($instance->hasDescription());
            self::assertNull($instance->getDescription());
            self::assertNull($instance->description);
        } else {
            self::assertTrue($instance->hasDescription());
            self::assertEquals($options['description'], $instance->getDescription());
            self::assertEquals($options['description'], $instance->description);
        }

        $instance->setDescription('');
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
            self::assertEquals($options['description'], $instance->getDescription());
            self::assertEquals($options['description'], $instance->description);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testDeal($options): void
    {
        $instance = new CreateRefundRequest();

        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $instance->setDeal($options['deal']);
        if (empty($options['deal'])) {
            self::assertFalse($instance->hasDeal());
            self::assertNull($instance->getDeal());
            self::assertNull($instance->deal);
        } else {
            self::assertTrue($instance->hasDeal());
            if (is_array($options['deal'])) {
                self::assertEquals($options['deal'], $instance->getDeal()->toArray());
                self::assertEquals($options['deal'], $instance->deal->toArray());
            } else {
                self::assertEquals($options['deal']->toArray(), $instance->getDeal()->toArray());
                self::assertEquals($options['deal']->toArray(), $instance->deal->toArray());
            }
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
            if (is_array($options['deal'])) {
                self::assertEquals($options['deal'], $instance->getDeal()->toArray());
                self::assertEquals($options['deal'], $instance->deal->toArray());
            } else {
                self::assertEquals($options['deal']->toArray(), $instance->getDeal()->toArray());
                self::assertEquals($options['deal']->toArray(), $instance->deal->toArray());
            }
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
        $instance = new CreateRefundRequest();
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
        $instance = new CreateRefundRequest();
        $instance->deal = $value;
    }

    public function testValidate(): void
    {
        $instance = new CreateRefundRequest();

        self::assertFalse($instance->validate());
        $instance->setAmount(new MonetaryAmount());
        self::assertFalse($instance->validate());
        $instance->setAmount(new MonetaryAmount(Random::int(1, 100000)));
        self::assertFalse($instance->validate());
        $instance->setDeal([
            'refund_settlements' => [
                [
                    'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                    'amount' => [
                        'value' => round(Random::float(1.00, 100.00), 2),
                        'currency' => 'RUB',
                    ],
                ],
            ],
        ]);
        self::assertFalse($instance->validate());
        $instance->setPaymentId(Random::str(36));
        self::assertTrue($instance->validate());

        $receipt = new Receipt();
        $receipt->setItems([
            [
                'description' => Random::str(10),
                'quantity' => (float) Random::int(1, 10),
                'amount' => [
                    'value' => round(Random::float(1, 100), 2),
                    'currency' => CurrencyCode::RUB,
                ],
                'vat_code' => Random::int(1, 6),
                'payment_subject' => PaymentSubject::COMMODITY,
                'payment_mode' => PaymentMode::PARTIAL_PREPAYMENT,
            ],
        ]);
        $instance->setReceipt($receipt);
        $item = new ReceiptItem();
        $item->setPrice(new ReceiptItemAmount(10));
        $item->setDescription('test');
        $receipt->addItem($item);
        self::assertFalse($instance->validate());
        $receipt->getCustomer()->setPhone('123123');
        self::assertTrue($instance->validate());
        $item->setVatCode(3);
        self::assertTrue($instance->validate());
        $receipt->setTaxSystemCode(4);
        self::assertTrue($instance->validate());

        self::assertTrue($instance->hasReceipt());
        $instance->removeReceipt();
        self::assertFalse($instance->hasReceipt());
    }

    /**
     * @dataProvider invalidReceiptDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceipt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateRefundRequest();
        $instance->setReceipt($value);
    }

    /**
     * @dataProvider invalidReceiptDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidReceipt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreateRefundRequest();
        $instance->receipt = $value;
    }

    public function testBuilder(): void
    {
        $builder = CreateRefundRequest::builder();
        self::assertInstanceOf(CreateRefundRequestBuilder::class, $builder);
    }

    /**
     * @dataProvider invalidSourceDataProvider
     *
     * @param mixed $value
     */
    public function testInvalidSetSources($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $instance = new CreateRefundRequest();
        $instance->setSources($value);
    }

    public static function validDataProvider(): array
    {
        $result = [
            [
                [
                    'paymentId' => Random::str(36),
                    'amount' => new MonetaryAmount(Random::int(1, 100)),
                    'description' => null,
                    'deal' => null,
                ],
            ],
            [
                [
                    'paymentId' => Random::str(36),
                    'amount' => new MonetaryAmount(Random::int(1, 100)),
                    'description' => '',
                    'deal' => '',
                ],
            ],
            [
                [
                    'paymentId' => Random::str(36),
                    'amount' => new MonetaryAmount(Random::int(1, 100)),
                    'description' => '',
                    'deal' => new RefundDealData([
                        'refund_settlements' => [
                            [
                                'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                                'amount' => [
                                    'value' => round(Random::float(1.00, 100.00), 2),
                                    'currency' => 'RUB',
                                ],
                            ],
                        ],
                    ]),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'paymentId' => Random::str(36),
                'amount' => new MonetaryAmount(Random::int(1, 100)),
                'description' => uniqid('', true),
                'deal' => [
                    'refund_settlements' => [
                        [
                            'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                            'amount' => [
                                'value' => round(Random::float(1.00, 100.00), 2),
                                'currency' => 'RUB',
                            ],
                        ],
                    ],
                ],
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidReceiptDataProvider(): array
    {
        return [
            [1],
            ['test'],
            [true],
            [false],
            [new stdClass()],
        ];
    }

    public static function invalidPaymentIdDataProvider(): array
    {
        return [
            [''],
            [null],
            [1],
            [Random::str(35)],
            [Random::str(37)],
        ];
    }

    public static function invalidDescriptionDataProvider(): array
    {
        return [
            [[]],
            [new stdClass()],
        ];
    }

    public static function invalidSourceDataProvider(): array
    {
        return [
            [Random::str(35)],
        ];
    }

    public static function invalidDealDataProvider(): array
    {
        return [
            [Random::str(35)],
            [new stdClass()],
        ];
    }
}
