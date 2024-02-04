<?php

namespace Tests\YooKassa\Request\Receipts;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\ReceiptCustomer;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Model\Receipt\ReceiptType;
use YooKassa\Model\Receipt\Settlement;
use YooKassa\Model\Receipt\SettlementType;
use YooKassa\Request\Payments\Airline;
use YooKassa\Request\Payments\CreatePaymentRequest;
use YooKassa\Request\Payments\CreatePaymentRequestBuilder;
use YooKassa\Request\Receipts\CreatePostReceiptRequest;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;

/**
 * @internal
 */
class CreatePostReceiptRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCustomer($options): void
    {
        $instance = new CreatePostReceiptRequest();

        self::assertFalse($instance->hasCustomer());
        self::assertNull($instance->getCustomer());
        self::assertNull($instance->customer);

        $instance->setCustomer($options['customer']);
        if (empty($options['customer'])) {
            self::assertFalse($instance->hasCustomer());
            self::assertNull($instance->getCustomer());
            self::assertNull($instance->customer);
        } else {
            self::assertTrue($instance->hasCustomer());
            self::assertSame($options['customer'], $instance->getCustomer()->jsonSerialize());
            self::assertSame($options['customer'], $instance->customer->jsonSerialize());
        }

        $instance->customer = $options['customer'];
        if (empty($options['customer'])) {
            self::assertFalse($instance->hasCustomer());
            self::assertNull($instance->getCustomer());
            self::assertNull($instance->customer);
        } else {
            self::assertTrue($instance->hasCustomer());
            self::assertSame($options['customer'], $instance->getCustomer()->jsonSerialize());
            self::assertSame($options['customer'], $instance->customer->jsonSerialize());
        }
    }

    /**
     * @dataProvider invalidCustomerDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCustomer($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePostReceiptRequest();
        $instance->setCustomer($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testType($options): void
    {
        $instance = new CreatePostReceiptRequest();

        $instance->setType($options['type']);

        self::assertSame($options['type'], $instance->getType());
        self::assertSame($options['type'], $instance->type);
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePostReceiptRequest();
        $instance->setType($value);
    }

    public function testValidate(): void
    {
        $instance = new CreatePostReceiptRequest();

        self::assertFalse($instance->validate());

        $instance->setCustomer(new ReceiptCustomer());
        self::assertFalse($instance->validate());

        $instance->setCustomer(new ReceiptCustomer(['email' => 'johndoe@email.com']));
        self::assertFalse($instance->validate());

        $instance->setType(ReceiptType::REFUND);
        self::assertFalse($instance->validate());

        $instance->setType(ReceiptType::PAYMENT);
        self::assertFalse($instance->validate());

        $instance->setSend(true);
        self::assertFalse($instance->validate());

        $instance->setObjectId(uniqid('', true));
        self::assertFalse($instance->validate());

        $instance->setSettlements([
            new Settlement([
                'type' => SettlementType::PREPAYMENT,
                'amount' => new ReceiptItemAmount(10, 'RUB')]),
        ]);
        self::assertFalse($instance->validate());

        $instance->setItems([
            new ReceiptItem([
                'description' => 'description',
                'amount' => [
                    'value' => 10,
                    'currency' => 'RUB',
                ],
                'quantity' => 1,
                'vat_code' => 1,
            ]),
        ]);
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = CreatePaymentRequest::builder();
        self::assertInstanceOf(CreatePaymentRequestBuilder::class, $builder);
    }

    /**
     * @dataProvider fromArrayCustomerDataProvider
     */
    public function testCustomerFromArray(array $source, array $expected): void
    {
        $receiptPost = new CreatePostReceiptRequest();
        $receiptPost->fromArray($source);

        if (!empty($expected)) {
            foreach ($expected as $property => $value) {
                self::assertEquals($value, $receiptPost->offsetGet($property));
            }
        } else {
            self::assertEmpty($receiptPost->getCustomer());
        }
    }

    /**
     * @dataProvider fromArraySettlementDataProvider
     *
     * @throws Exception
     */
    public function testSettlementFromArray(array $options): void
    {
        $receiptPost = new CreatePostReceiptRequest();
        $receiptPost->fromArray($options);

        self::assertEquals(count($options['settlements']), count($receiptPost->getSettlements()));
        self::assertFalse($receiptPost->notEmpty());

        foreach ($receiptPost->getSettlements() as $index => $item) {
            self::assertInstanceOf(Settlement::class, $item);
            self::assertArrayHasKey($index, $options['settlements']);
            self::assertEquals($options['settlements'][$index]['type'], $item->getType());
            self::assertEquals($options['settlements'][$index]['amount'], $item->getAmount());
        }
    }

    /**
     * @dataProvider invalidSetsDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidItems($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePostReceiptRequest();
        $instance->setItems($value);
    }

    public function testGetObjectId(): void
    {
        $instance = new CreatePostReceiptRequest();
        $instance->setObjectId('test');
        self::assertSame('test', $instance->getObjectId());
    }

    /**
     * @dataProvider invalidTaxSystemCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidTaxSystemCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePostReceiptRequest();
        $instance->setTaxSystemCode($value);
    }

    public function testSetTaxSystemCode(): void
    {
        $instance = new CreatePostReceiptRequest();
        $instance->setTaxSystemCode(3);
        self::assertSame(3, $instance->getTaxSystemCode());
    }

    /**
     * @dataProvider invalidSetsDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidSettlements($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePostReceiptRequest();
        $instance->setSettlements($value);
    }

    public function testSetItems(): void
    {
        $instance = new CreatePostReceiptRequest();
        $instance->setItems(
            [
                [
                    'description' => 'description',
                    'amount' => [
                        'value' => 10,
                        'currency' => 'RUB',
                    ],
                    'quantity' => 1,
                    'vat_code' => 1,
                ],
            ]
        );
        self::assertSame([
            'description' => 'description',
            'quantity' => 1.0,
            'amount' => [
                'value' => '10.00',
                'currency' => 'RUB',
            ],
            'vat_code' => 1,
        ], $instance->getItems()[0]->toArray());
    }

    /**
     * @dataProvider invalidSetOnBehalfOfDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidOnBehalfOf($value): void
    {
        $this->expectException(TypeError::class);
        $instance = new CreatePostReceiptRequest();
        $instance->setOnBehalfOf($value);
    }

    public function testsetOnBehalfOf(): void
    {
        $instance = new CreatePostReceiptRequest();
        $instance->setOnBehalfOf('test');
        self::assertSame('test', $instance->getOnBehalfOf());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSend($options): void
    {
        $instance = new CreatePostReceiptRequest();

        self::assertTrue($instance->getSend());
        self::assertTrue($instance->send);

        $instance->setSend($options['send']);

        self::assertSame($options['send'], $instance->getSend());
        self::assertSame($options['send'], $instance->send);
    }

    public static function fromArrayCustomerDataProvider(): array
    {
        $customer = new ReceiptCustomer();
        $customer->setFullName('John Doe');
        $customer->setEmail('johndoe@yoomoney.ru');
        $customer->setPhone('79000000000');
        $customer->setInn('6321341814');

        return [
            [
                [],
                [],
            ],

            [
                [
                    'customer' => [
                        'fullName' => 'John Doe',
                        'email' => 'johndoe@yoomoney.ru',
                        'phone' => '79000000000',
                        'inn' => '6321341814',
                    ],
                ],
                [
                    'customer' => $customer,
                ],
            ],

            [
                [
                    'customer' => [
                        'full_name' => 'John Doe',
                        'inn' => '6321341814',
                        'email' => 'johndoe@yoomoney.ru',
                        'phone' => '79000000000',
                    ],
                ],
                [
                    'customer' => $customer,
                ],
            ],
        ];
    }

    /**
     * @return array[][][]
     *
     * @throws Exception
     */
    public function fromArraySettlementDataProvider(): array
    {
        return [
            [
                [
                    'settlements' => $this->generateSettlements(),
                ],
            ],
        ];
    }

    /**
     * @return array[][]
     */
    public static function fromArrayDataProvider(): array
    {
        $receiptItem = new ReceiptItem();
        $receiptItem->setDescription('test');
        $receiptItem->setQuantity(322);
        $receiptItem->setVatCode(4);
        $receiptItem->setPrice(new ReceiptItemAmount(5, 'USD'));

        return [
            [
                [],
                [],
            ],

            [
                [
                    'taxSystemCode' => 2,
                    'customer' => [
                        'phone' => '1234567890',
                        'email' => 'test@tset',
                    ],
                    'items' => [
                        new ReceiptItem(),
                    ],
                ],
                [
                    'tax_system_code' => 2,
                    'customer' => new ReceiptCustomer([
                        'phone' => '1234567890',
                        'email' => 'test@tset',
                    ]),
                    'items' => [
                        new ReceiptItem(),
                    ],
                ],
            ],

            [
                [
                    'tax_system_code' => 3,
                    'customer' => [
                        'phone' => '1234567890',
                        'email' => 'test@tset',
                    ],
                    'items' => [
                        [
                            'description' => 'test',
                            'quantity' => 322,
                            'amount' => [
                                'value' => 5,
                                'currency' => 'USD',
                            ],
                            'vat_code' => 4,
                        ],
                        new ReceiptItem(),
                        [
                            'description' => 'test',
                            'quantity' => 322,
                            'amount' => new ReceiptItemAmount(5, 'USD'),
                            'vat_code' => 4,
                        ],
                        [
                            'description' => 'test',
                            'quantity' => 322,
                            'amount' => new ReceiptItemAmount([
                                'value' => 5,
                                'currency' => 'USD',
                            ]),
                            'vat_code' => 4,
                        ],
                    ],
                ],
                [
                    'taxSystemCode' => 3,
                    'customer' => new ReceiptCustomer([
                        'phone' => '1234567890',
                        'email' => 'test@tset',
                    ]),
                    'items' => [
                        $receiptItem,
                        new ReceiptItem(),
                        $receiptItem,
                        $receiptItem,
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $type = Random::value(ReceiptType::getEnabledValues());
        $result = [
            [
                [
                    'customer' => [
                        'full_name' => Random::str(128),
                        'email' => 'johndoe@yoomoney.ru',
                        'phone' => Random::str(4, 12, '1234567890'),
                        'inn' => '1234567890',
                    ],
                    'items' => [
                        [
                            'description' => Random::str(128),
                            'quantity' => Random::int(1, 10),
                            'amount' => [
                                'value' => Random::float(0.1, 99.99),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                            'vat_code' => Random::int(1, 6),
                            'payment_subject' => Random::value(PaymentSubject::getValidValues()),
                            'payment_mode' => Random::value(PaymentMode::getValidValues()),
                            'product_code' => Random::str(128),
                            'country_of_origin_code' => 'RU',
                            'customs_declaration_number' => Random::str(128),
                            'excise' => Random::float(0.0, 99.99),
                        ],
                    ],
                    'tax_system_code' => Random::int(1, 6),
                    'type' => $type,
                    'send' => true,
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementType::getValidValues()),
                            'amount' => [
                                'value' => Random::float(0.1, 99.99),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ],
                    ],
                    $type . '_id' => uniqid('', true),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $type = Random::value(ReceiptType::getEnabledValues());
            $request = [
                'customer' => [
                    'full_name' => Random::str(128),
                    'email' => 'johndoe@yoomoney.ru',
                    'phone' => Random::str(4, 12, '1234567890'),
                    'inn' => '1234567890',
                ],
                'items' => [
                    [
                        'description' => Random::str(128),
                        'quantity' => Random::int(1, 10),
                        'amount' => [
                            'value' => Random::float(0.1, 99.99),
                            'currency' => Random::value(CurrencyCode::getValidValues()),
                        ],
                        'vat_code' => Random::int(1, 6),
                        'payment_subject' => Random::value(PaymentSubject::getValidValues()),
                        'payment_mode' => Random::value(PaymentMode::getValidValues()),
                        'product_code' => Random::str(128),
                        'country_of_origin_code' => 'RU',
                        'customs_declaration_number' => Random::str(128),
                        'excise' => Random::float(0.0, 99.99),
                    ],
                ],
                'tax_system_code' => Random::int(1, 6),
                'type' => $type,
                'send' => true,
                'settlements' => [
                    [
                        'type' => Random::value(SettlementType::getValidValues()),
                        'amount' => [
                            'value' => Random::float(0.1, 99.99),
                            'currency' => Random::value(CurrencyCode::getValidValues()),
                        ],
                    ],
                ],
                $type . '_id' => uniqid('', true),
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidCustomerDataProvider()
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
            [new stdClass()],
        ];
    }

    public static function invalidTypeDataProvider()
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
        ];
    }

    public static function invalidSetsDataProvider()
    {
        return [
            [[]],
            [null],
        ];
    }

    public static function invalidTaxSystemCodeDataProvider()
    {
        return [
            [false],
            [0],
        ];
    }

    public static function invalidSetOnBehalfOfDataProvider()
    {
        return [
            [new stdClass()],
        ];
    }

    /**
     * @throws Exception
     */
    private function generateSettlements(): array
    {
        $return = [];
        $count = Random::int(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $return[] = $this->generateSettlement(0 === $i % 2);
        }

        return $return;
    }

    /**
     * @param mixed $true
     *
     * @return array|Settlement
     *
     * @throws Exception
     */
    private function generateSettlement($true)
    {
        $params = [
            'type' => Random::value(SettlementType::getValidValues()),
            'amount' => new MonetaryAmount(Random::int(1, 1000)),
        ];

        return $true ? $params : new Settlement($params);
    }
}
