<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Common\ListObject;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\AdditionalUserProps;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\OperationalDetails;
use YooKassa\Model\Receipt\Receipt;
use YooKassa\Model\Receipt\ReceiptCustomer;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Model\Receipt\Settlement;
use YooKassa\Model\Receipt\SettlementType;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;

/**
 * @internal
 */
class ReceiptTest extends TestCase
{
    public function testGetSetAddItems(): void
    {
        $instance = new Receipt();

        self::assertNotNull($instance->getItems());
        self::assertEquals(ReceiptItem::class, $instance->getItems()->getType());
        self::assertEmpty($instance->getItems());

        self::assertNotNull($instance->items);
        self::assertEquals(ReceiptItem::class, $instance->items->getType());
        self::assertEmpty($instance->items);

        $item = new ReceiptItem();
        $instance->addItem($item);
        $items = $instance->getItems();
        self::assertSame(count($items), 1);
        foreach ($items as $tmp) {
            self::assertSame($item, $tmp);
        }

        $this->expectException(EmptyPropertyValueException::class);
        $instance->setItems([]);

        self::assertNotNull($instance->getItems());
        self::assertIsArray($instance->getItems());
        self::assertEmpty($instance->getItems());

        $instance->setItems($items);
        self::assertSame(count($items), 1);
        foreach ($items as $tmp) {
            self::assertSame($item, $tmp);
        }
    }

    /**
     * @dataProvider invalidItemsProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidItems(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Receipt();
        $instance->setItems($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetTaxSystemCode($options): void
    {
        $instance = new Receipt();

        $instance->setTaxSystemCode($options['tax_system_code']);
        self::assertEquals($options['tax_system_code'], $instance->getTaxSystemCode());
        self::assertEquals($options['tax_system_code'], $instance->taxSystemCode);
        self::assertEquals($options['tax_system_code'], $instance->tax_system_code);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterTaxSystemCode($options): void
    {
        $instance = new Receipt();

        self::assertNull($instance->getTaxSystemCode());
        self::assertNull($instance->taxSystemCode);
        self::assertNull($instance->tax_system_code);

        $instance->taxSystemCode = $options['tax_system_code'];
        self::assertEquals($options['tax_system_code'], $instance->getTaxSystemCode());
        self::assertEquals($options['tax_system_code'], $instance->taxSystemCode);
        self::assertEquals($options['tax_system_code'], $instance->tax_system_code);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetAdditionalUserProps($options): void
    {
        $instance = new Receipt();

        self::assertNull($instance->getAdditionalUserProps());
        self::assertNull($instance->additionalUserProps);
        self::assertNull($instance->additional_user_props);

        if (!empty($options['additional_user_props'])) {
            $instance->setAdditionalUserProps($options['additional_user_props']);
            if (is_array($options['additional_user_props'])) {
                self::assertEquals($options['additional_user_props'], $instance->getAdditionalUserProps()->toArray());
                self::assertEquals($options['additional_user_props'], $instance->additionalUserProps->toArray());
                self::assertEquals($options['additional_user_props'], $instance->additional_user_props->toArray());
            } else {
                self::assertEquals($options['additional_user_props'], $instance->getAdditionalUserProps());
                self::assertEquals($options['additional_user_props'], $instance->additionalUserProps);
                self::assertEquals($options['additional_user_props'], $instance->additional_user_props);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterAdditionalUserProps($options): void
    {
        $instance = new Receipt();

        self::assertNull($instance->getAdditionalUserProps());
        self::assertNull($instance->additionalUserProps);
        self::assertNull($instance->additional_user_props);

        if (!empty($options['additional_user_props'])) {
            $instance->additionalUserProps = $options['additional_user_props'];
            if (is_array($options['additional_user_props'])) {
                self::assertEquals($options['additional_user_props'], $instance->getAdditionalUserProps()->toArray());
                self::assertEquals($options['additional_user_props'], $instance->additionalUserProps->toArray());
                self::assertEquals($options['additional_user_props'], $instance->additional_user_props->toArray());
            } else {
                self::assertEquals($options['additional_user_props'], $instance->getAdditionalUserProps());
                self::assertEquals($options['additional_user_props'], $instance->additionalUserProps);
                self::assertEquals($options['additional_user_props'], $instance->additional_user_props);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetReceiptIndustryDetails($options): void
    {
        $instance = new Receipt();
        $instance->setReceiptIndustryDetails($options['receipt_industry_details']);

        self::assertEquals($options['receipt_industry_details'], $instance->getReceiptIndustryDetails()->toArray());

        self::assertCount(count($options['receipt_industry_details']), $instance->getReceiptIndustryDetails());
        if (!empty($options['receipt_industry_details'])) {
            self::assertEquals($options['receipt_industry_details'][0], $instance->getReceiptIndustryDetails()[0]->toArray());
        }
    }

    /**
     * @dataProvider invalidReceiptIndustryDetailsDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceiptIndustryDetails($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Receipt();
        $instance->setReceiptIndustryDetails($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testAddReceiptIndustryDetails($options): void
    {
        $instance = new Receipt();
        foreach ($options['receipt_industry_details'] as $item) {
            $instance->addReceiptIndustryDetails($item);
        }

        self::assertCount(count($options['receipt_industry_details']), $instance->getReceiptIndustryDetails());
        if (!empty($options['receipt_industry_details'])) {
            self::assertEquals($options['receipt_industry_details'][0], $instance->getReceiptIndustryDetails()[0]->toArray());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetReceiptOperationalDetails($options): void
    {
        $instance = new Receipt();

        $instance->setReceiptOperationalDetails($options['receipt_operational_details']);

        if (is_array($options['receipt_operational_details'])) {
            self::assertEquals($options['receipt_operational_details'], $instance->getReceiptOperationalDetails()->toArray());
        } else {
            self::assertEquals($options['receipt_operational_details'], $instance->getReceiptOperationalDetails());
        }
    }

    /**
     * @dataProvider invalidReceiptOperationalDetailsDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceiptOperationalDetails($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Receipt();
        $instance->setReceiptOperationalDetails($value);
    }


    /**
     * @dataProvider validDataProvider
     * @param $options
     */
    public function testSetterTax_system_code($options)
    {
        $instance = new Receipt();

        self::assertNull($instance->getTaxSystemCode());
        self::assertNull($instance->taxSystemCode);
        self::assertNull($instance->tax_system_code);

        $instance->tax_system_code = $options['tax_system_code'];
        self::assertEquals($options['tax_system_code'], $instance->getTaxSystemCode());
        self::assertEquals($options['tax_system_code'], $instance->taxSystemCode);
        self::assertEquals($options['tax_system_code'], $instance->tax_system_code);
    }

    /**
     * @dataProvider invalidTaxSystemIdProvider
     *
     * @param mixed $value
     * @param string $exception
     */
    public function testSetInvalidTaxSystemCode(mixed $value, string $exception): void
    {
        $this->expectException($exception);
        $instance = new Receipt();
        $instance->setTaxSystemCode($value);
    }

    /**
     *
     */
    public function testNotEmpty()
    {
        $instance = new Receipt();

        self::assertFalse($instance->notEmpty());
        $instance->addItem(new ReceiptItem());
        self::assertTrue($instance->notEmpty());
    }

    /**
     * @dataProvider validSettlementsDataProvider
     *
     * @param mixed $value
     */
    public function testSetSettlements($value): void
    {
        $instance = new Receipt();
        $instance->setSettlements($value);

        self::assertCount(count($value), $instance->getSettlements());
        self::assertEquals($value[0], $instance->getSettlements()[0]);
    }

    /**
     * @dataProvider validSettlementsDataProvider
     *
     * @param mixed $value
     */
    public function testAddSettlements($value): void
    {
        $instance = new Receipt();
        foreach ($value as $item) {
            $instance->addSettlement($item);
        }

        self::assertCount(count($value), $instance->getSettlements());
        self::assertEquals($value[0], $instance->getSettlements()[0]);
    }

    /**
     * @dataProvider invalidSettlementsDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testInvalidSettlementsData(mixed $value, string $exceptionClassName): void
    {
        $instance = new Receipt();

        $this->expectException($exceptionClassName);
        $instance->setSettlements($value);
    }

    public static function validDataProvider()
    {
        $result = [
            [
                [
                    'items' => [],
                    'tax_system_code' => null,
                    'customer' => [
                        'phone' => '',
                        'email' => '',
                    ],
                    'additional_user_props' => [
                        'name' => Random::str(AdditionalUserProps::NAME_MAX_LENGTH),
                        'value' => Random::str(AdditionalUserProps::VALUE_MAX_LENGTH),
                    ],
                    'settlements' => [
                        [
                            'type' => 'cashless',
                            'amount' => [
                                'value' => '10.00',
                                'currency' => 'RUB',
                            ],
                        ],
                    ],
                    'receipt_industry_details' => [],
                    'receipt_operational_details' => null,
                ],
            ],
            [
                [
                    'items' => [],
                    'tax_system_code' => 6,
                    'customer' => [
                        'phone' => '',
                        'email' => '',
                    ],
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementType::getValidValues()),
                            'amount' => [
                                'value' => round(Random::float(0.1, 99.99), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ],
                    ],
                    'receipt_industry_details' => [],
                    'receipt_operational_details' => null,
                ],
            ],
            [
                [
                    'items' => [],
                    'customer' => [
                        'phone' => '',
                        'email' => '',
                    ],
                    'tax_system_code' => 1,
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementType::getValidValues()),
                            'amount' => [
                                'value' => round(Random::float(0.1, 99.99), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ],
                    ],
                    'receipt_industry_details' => [],
                    'receipt_operational_details' => null,
                ],
            ],
            [
                [
                    'items' => [],
                    'customer' => [
                        'phone' => '',
                        'email' => '',
                    ],
                    'tax_system_code' => 2,
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementType::getValidValues()),
                            'amount' => [
                                'value' => round(Random::float(0.1, 99.99), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ],
                    ],
                    'receipt_industry_details' => [],
                    'receipt_operational_details' => null,
                ],
            ],
            [
                [
                    'items' => [],
                    'customer' => [
                        'phone' => '',
                        'email' => '',
                    ],
                    'tax_system_code' => 3,
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementType::getValidValues()),
                            'amount' => [
                                'value' => round(Random::float(0.1, 99.99), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ],
                    ],
                    'receipt_industry_details' => [],
                    'receipt_operational_details' => null,
                ],
            ],
        ];
        for ($i = 1; $i < 6; $i++) {
            $receipt = [
                'items' => [],
                'tax_system_code' => $i,
                'customer' => [
                    'phone' => Random::str(10, 10, '1234567890'),
                    'email' => uniqid('', true) . '@' . uniqid('', true),
                ],
                'receipt_industry_details' => [
                    [
                        'federal_id' => Random::value([
                            '00' . Random::int(1, 9),
                            '0' . Random::int(1, 6) . Random::int(0, 9),
                            '07' . Random::int(0, 3)
                        ]),
                        'document_date' => date(IndustryDetails::DOCUMENT_DATE_FORMAT),
                        'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                        'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                    ],
                ],
                'receipt_operational_details' => [
                    'operation_id' => Random::int(0, OperationalDetails::OPERATION_ID_MAX_VALUE),
                    'value' => Random::str(1, OperationalDetails::VALUE_MAX_LENGTH),
                    'created_at' => date(YOOKASSA_DATE),
                ],
            ];
            $result[] = [$receipt];
        }

        return $result;
    }

    public static function validSettlementsDataProvider()
    {
        $result = [
            [
                [new Settlement(
                    [
                        'type' => 'cashless',
                        'amount' => [
                            'value' => '10.00',
                            'currency' => 'RUB',
                        ],
                    ]
                ),
                ],
            ]];
        for ($i = 1; $i < 9; $i++) {
            $receipt = [
                new Settlement(
                    [
                        'type' => Random::value(SettlementType::getValidValues()),
                        'amount' => new MonetaryAmount(Random::int(1, 1000), 'RUB'),
                    ]
                ),
            ];
            $result[] = [$receipt];
        }

        return $result;
    }

    public static function invalidSettlementsDataProvider()
    {
        return [
            [[[]], EmptyPropertyValueException::class],
            [Random::str(10), InvalidPropertyValueTypeException::class],
        ];
    }

    public static function invalidItemsProvider()
    {
        return [
            [null],
            [new stdClass()],
            ['invalid_value'],
            [''],
            [0],
            [1],
            [true],
            [false],
            [1.43],
            [[]],
        ];
    }

    public static function invalidTaxSystemIdProvider()
    {
        return [
            [new stdClass(), TypeError::class],
            ['invalid_value', TypeError::class],
            [0, InvalidArgumentException::class],
            [3234, InvalidArgumentException::class],
            [false, InvalidArgumentException::class],
        ];
    }

    public function invalidPhoneProvider()
    {
        return [
            [new stdClass()],
            [[]],
            [true],
            [false],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidReceiptIndustryDetailsDataProvider(): array
    {
        return [
            [new stdClass()],
            [true],
            [Random::str(1, 100)],
        ];
    }

    public static function invalidReceiptOperationalDetailsDataProvider()
    {
        return [
            [new stdClass()],
            [true],
            [Random::str(1, 100)],
        ];
    }

    public function testGetAmountValue(): void
    {
        $receipt = new Receipt();
        self::assertEquals(0, $receipt->getAmountValue());

        $item = new ReceiptItem();
        $item->setPrice(new ReceiptItemAmount(Random::float(0.01, 99.99)));
        $item->setQuantity(Random::float(0.0001, 99.99));
        $receipt->addItem($item);

        $expected = (int) round($item->getPrice()->getIntegerValue() * $item->getQuantity());
        self::assertEquals($expected, $receipt->getAmountValue());
        self::assertEquals($expected, $receipt->getAmountValue(false));
        self::assertEquals(0, $receipt->getShippingAmountValue());

        $item = new ReceiptItem();
        $item->setPrice(new ReceiptItemAmount(Random::float(0.01, 99.99)));
        $item->setQuantity(Random::float(0.0001, 99.99));
        $receipt->addItem($item);

        $expected += (int) round($item->getPrice()->getIntegerValue() * $item->getQuantity());
        self::assertEquals($expected, $receipt->getAmountValue());
        self::assertEquals($expected, $receipt->getAmountValue(false));
        self::assertEquals(0, $receipt->getShippingAmountValue());

        $item = new ReceiptItem();
        $item->setPrice(new ReceiptItemAmount(Random::float(0.01, 99.99)));
        $item->setQuantity(Random::float(0.0001, 99.99));
        $item->setIsShipping(true);
        $receipt->addItem($item);

        $shipping = $expected;
        $expected += (int) round($item->getPrice()->getIntegerValue() * $item->getQuantity());

        self::assertEquals($expected, $receipt->getAmountValue());
        self::assertEquals($shipping, $receipt->getAmountValue(false));
        self::assertEquals($expected - $shipping, $receipt->getShippingAmountValue());

        $item = new ReceiptItem();
        $item->setPrice(new ReceiptItemAmount(Random::float(0.01, 99.99)));
        $item->setQuantity(Random::float(0.0001, 99.99));
        $item->setIsShipping(true);
        $receipt->addItem($item);

        $expected += (int) round($item->getPrice()->getIntegerValue() * $item->getQuantity());

        self::assertEquals($expected, $receipt->getAmountValue());
        self::assertEquals($shipping, $receipt->getAmountValue(false));
        self::assertEquals($expected - $shipping, $receipt->getShippingAmountValue());

        $item = new ReceiptItem();
        $item->setPrice(new ReceiptItemAmount(Random::float(0.01, 99.99)));
        $item->setQuantity(Random::float(0.0001, 99.99));
        $receipt->addItem($item);

        $shipping += (int) round($item->getPrice()->getIntegerValue() * $item->getQuantity());
        $expected += (int) round($item->getPrice()->getIntegerValue() * $item->getQuantity());
        self::assertEquals($expected, $receipt->getAmountValue());
        self::assertEquals($shipping, $receipt->getAmountValue(false));
        self::assertEquals($expected - $shipping, $receipt->getShippingAmountValue());
    }

    /**
     * @dataProvider validNormalizationDataProvider
     *
     * @param mixed $withShipping
     * @param mixed $items
     * @param mixed $amount
     * @param mixed $expected
     */
    public function testNormalize($items, $amount, $expected, $withShipping = false): void
    {
        $receipt = new Receipt();
        foreach ($items as $itemInfo) {
            $item = new ReceiptItem();
            $item->setPrice(new ReceiptItemAmount($itemInfo['price']));
            if (!empty($itemInfo['quantity'])) {
                $item->setQuantity($itemInfo['quantity']);
            } else {
                $item->setQuantity(1);
            }
            if (!empty($itemInfo['shipping'])) {
                $item->setIsShipping(true);
            }
            $receipt->addItem($item);
        }
        $receipt->normalize(new ReceiptItemAmount($amount), $withShipping);

        self::assertEquals(count($expected), count($receipt->getItems()));
        $expectedAmount = 0;
        foreach ($receipt->getItems() as $index => $item) {
            self::assertEquals($expected[$index]['price'], $item->getPrice()->getIntegerValue());
            self::assertEquals($expected[$index]['quantity'], $item->getQuantity());

            $expectedAmount += $item->getAmount();
        }
        self::assertEquals($expectedAmount, $amount * 100.0);
    }

    public static function validNormalizationDataProvider()
    {
        return [
            [
                [
                    ['price' => 10.0],
                ],
                9.0,
                [
                    ['price' => 900, 'quantity' => 1.0],
                ]
            ],
            [
                [
                    ['price' => 10.0],
                    ['price' => 20.0],
                ],
                29.0,
                [
                    ['price' => 967, 'quantity' => 1.0],
                    ['price' => 1933, 'quantity' => 1.0],
                ]
            ],
            [
                [
                    ['price' => 10.0, 'quantity' => 1],
                    ['price' => 20.0, 'quantity' => 3],
                ],
                29.0,
                [
                    ['price' => 413, 'quantity' => 1.0],
                    ['price' => 829, 'quantity' => 3.0],
                ]
            ],
            [
                [
                    ['price' => 50.0, 'quantity' => 3],
                    ['price' => 20.0, 'quantity' => 3],
                ],
                100.0,
                [
                    ['price' => 2381, 'quantity' => 2.0],
                    ['price' => 2382, 'quantity' => 1.0],
                    ['price' => 952, 'quantity' => 3.0],
                ]
            ],
            [
                [
                    ['price' => 10.0, 'shipping' => true],
                    ['price' => 50.0, 'quantity' => 3],
                    ['price' => 10.0, 'shipping' => true],
                    ['price' => 20.0, 'quantity' => 3],
                ],
                120.0,
                [
                    ['price' => 1000, 'quantity' => 1.0],
                    ['price' => 2381, 'quantity' => 2.0],
                    ['price' => 2382, 'quantity' => 1.0],
                    ['price' => 1000, 'quantity' => 1.0],
                    ['price' => 952, 'quantity' => 3.0],
                ]
            ],
            [
                [
                    ['price' => 50.0, 'quantity' => 1, 'shipping' => 1],
                    ['price' => 50.0, 'quantity' => 2],
                    ['price' => 20.0, 'quantity' => 3],
                ],
                100.0,
                [
                    ['price' => 2381, 'quantity' => 1.0],
                    ['price' => 2381, 'quantity' => 1.0],
                    ['price' => 2382, 'quantity' => 1.0],
                    ['price' => 952, 'quantity' => 3.0],
                ],
                true
            ],
            [
                [
                    ['price' => 50.0, 'quantity' => 1, 'shipping' => 1],
                ],
                49.0,
                [
                    ['price' => 4900, 'quantity' => 1.0],
                ],
                true
            ],
            [
                [
                    ['price' => 100.0, 'quantity' => 0.5],
                    ['price' => 100.0, 'quantity' => 0.4],
                ],
                98.0,
                [
                    ['price' => 10889, 'quantity' => 0.25],
                    ['price' => 10888, 'quantity' => 0.25],
                    ['price' => 10889, 'quantity' => 0.4],
                ],
                true
            ],
            [
                [
                    ['price' => 10, 'quantity' => 1],
                    ['price' => 300, 'quantity' => 1, 'shipping' => 1],
                ],
                10.0,
                [
                    ['price' => 32, 'quantity' => 1],
                    ['price' => 968, 'quantity' => 1, 'shipping' => 1],
                ],
                true
            ],
            [
                [
                    ['price' => 10, 'quantity' => 1],
                    ['price' => 300, 'quantity' => 1, 'shipping' => 1],
                ],
                10.0,
                [
                    ['price' => 32, 'quantity' => 1],
                    ['price' => 968, 'quantity' => 1, 'shipping' => 1],
                ],
                false
            ],
            [
                [
                    ['price' => 0.01, 'quantity' => 1],
                    ['price' => 0.02, 'quantity' => 1],
                    ['price' => 0.03, 'quantity' => 1],
                    ['price' => 300, 'quantity' => 1, 'shipping' => 1],
                ],
                0.06,
                [
                    ['price' => 1, 'quantity' => 1],
                    ['price' => 1, 'quantity' => 1],
                    ['price' => 1, 'quantity' => 1],
                    ['price' => 3, 'quantity' => 1, 'shipping' => 1],
                ],
                false
            ],
            [
                [
                    ['price' => 0.01, 'quantity' => 7],
                    ['price' => 0.02, 'quantity' => 11],
                    ['price' => 0.03, 'quantity' => 13],
                    ['price' => 300, 'quantity' => 1, 'shipping' => 1],
                ],
                0.60,
                [
                    ['price' => 1, 'quantity' => 7],
                    ['price' => 1, 'quantity' => 11],
                    ['price' => 1, 'quantity' => 13],
                    ['price' => 29, 'quantity' => 1, 'shipping' => 1],
                ],
                false
            ],
            [
                [
                    ['price' => 0.01, 'quantity' => 7],
                    ['price' => 0.02, 'quantity' => 11],
                    ['price' => 10, 'quantity' => 1],
                    ['price' => 300, 'quantity' => 1, 'shipping' => 1],
                ],
                10.29,
                [
                    ['price' => 1, 'quantity' => 7],
                    ['price' => 1, 'quantity' => 11],
                    ['price' => 33, 'quantity' => 1],
                    ['price' => 978, 'quantity' => 1, 'shipping' => 1],
                ],
                false
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     * @param array $source
     * @param array $expected
     */
    public function testFromArray(array $source, array $expected): void
    {
        $receipt = new Receipt($source);

        if (!empty($expected)) {
            foreach ($expected as $property => $value) {
                $propertyValue = $receipt->offsetGet($property);
                if ($propertyValue instanceof ListObject) {
                    self::assertEquals($value, $propertyValue->getItems()->toArray());
                } else {
                    self::assertEquals($value, $propertyValue);
                }
            }
        } else {
            self::assertEquals(array(), $receipt->getItems()->toArray());
            self::assertEquals(array(), $receipt->getSettlements()->toArray());
        }
    }

    public function testGetObjectId()
    {
        $instance = new Receipt();
        self::assertNull($instance->getObjectId());
    }

    public function fromArrayDataProvider(): array
    {
        $receiptItem = new ReceiptItem();
        $receiptItem->setDescription('test');
        $receiptItem->setQuantity(322);
        $receiptItem->setVatCode(4);
        $receiptItem->setPrice(new ReceiptItemAmount(5, 'USD'));

        $settlement = new Settlement();
        $settlement->setType(SettlementType::PREPAYMENT);
        $settlement->setAmount(new MonetaryAmount(123, 'USD'));

        return [
            [
                [],
                [],
            ],

            [
                [
                    'description' => Random::str(2, 128),
                    'taxSystemCode' => 2,
                    'customer' => [
                        'phone' => '1234567890',
                        'email' => 'test@tset.ru',
                    ],
                    'items' => [
                        [
                            'description' => 'test',
                            'amount' => [
                                'value' => 5,
                                'currency' => CurrencyCode::USD,
                            ],
                            'quantity' => 322,
                            'vat_code' => 4,
                        ],
                    ],
                    'settlements' => [
                        [
                            'type' => SettlementType::PREPAYMENT,
                            'amount' => [
                                'value' => 123,
                                'currency' => CurrencyCode::USD,
                            ],
                        ]
                    ]
                ],
                [
                    'tax_system_code' => 2,
                    'customer' => new ReceiptCustomer([
                        'phone' => '1234567890',
                        'email' => 'test@tset.ru',
                    ]),
                    'items' => [
                        $receiptItem,
                    ],
                    'settlements' => [
                        $settlement,
                    ]
                ],
            ],

            [
                [
                    'tax_system_code' => 3,
                    'customer' => [
                        'phone' => '1234567890',
                        'email' => 'test@tset.com',
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
                    'settlements' => [
                        [
                            'type' => SettlementType::PREPAYMENT,
                            'amount' => [
                                'value' => 123,
                                'currency' => 'USD'
                            ]
                        ],
                        [
                            'type' => SettlementType::PREPAYMENT,
                            'amount' => [
                                'value' => 123,
                                'currency' => 'USD'
                            ]
                        ]
                    ],
                    'receipt_operational_details' => [
                        'operation_id' => 255,
                        'value' => '00-tr-589',
                        'created_at' => '2012-11-03T11:52:31.827Z',
                    ],
                ],
                [
                    'taxSystemCode' => 3,
                    'customer' => new ReceiptCustomer([
                        'phone' => '1234567890',
                        'email' => 'test@tset.com',
                    ]),
                    'items' => [
                        $receiptItem,
                        $receiptItem,
                        $receiptItem,
                    ],
                    'settlements' => [
                        $settlement,
                        $settlement
                    ],
                    'receipt_operational_details' => new OperationalDetails([
                        'operation_id' => 255,
                        'value' => '00-tr-589',
                        'created_at' => '2012-11-03T11:52:31.827Z',
                    ]),
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayCustomerDataProvider
     * @param array $source
     * @param array $expected
     */
    public function testCustomerFromArray($source, $expected): void
    {
        $receipt = new Receipt();
        $receipt->fromArray($source);

        if (!empty($expected)) {
            foreach ($expected as $property => $value) {
                self::assertEquals($value, $receipt->offsetGet($property));
            }
        } else {
            self::assertEquals(true, $receipt->getCustomer()->isEmpty());
        }
    }

    public function fromArrayCustomerDataProvider(): array
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
                    'customer' => $customer
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
                    'customer' => $customer
                ],
            ]
        ];
    }
}
