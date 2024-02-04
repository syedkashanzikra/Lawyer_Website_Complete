<?php

namespace Tests\YooKassa\Request\Receipts;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\ProductCode;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\AgentType;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\MarkCodeInfo;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\ReceiptItemMeasure;
use YooKassa\Model\Receipt\Supplier;
use YooKassa\Model\Receipt\SupplierInterface;
use YooKassa\Request\Payments\Airline;
use YooKassa\Request\Receipts\ReceiptResponseItem;

/**
 * @internal
 */
class ReceiptResponseItemTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetDescription(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['description'], $instance->getDescription());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetAmount(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertNotNull($instance->getAmount());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetVatCode(array $options): void
    {
        $instance = new ReceiptResponseItem($options);

        $instance->setVatCode($options['vat_code']);
        self::assertNotNull($instance->getVatCode());
        self::assertEquals($options['vat_code'], $instance->getVatCode());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetExcise(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setExcise(null);
        self::assertNull($instance->getExcise());
        if (empty($options['excise'])) {
            self::assertNull($instance->getExcise());
        } else {
            $instance->setExcise($options['excise']);
            self::assertNotNull($instance->getExcise());
            self::assertEquals($options['excise'], $instance->getExcise());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSupplier(array $options): void
    {
        $instance = new ReceiptResponseItem($options);

        if (empty($options['supplier'])) {
            self::assertNull($instance->getSupplier());
        } else {
            self::assertNotNull($instance->getSupplier());
            if (!is_object($instance->getSupplier())) {
                self::assertEquals($options['supplier'], $instance->getSupplier()->jsonSerialize());
            } else {
                self::assertTrue($instance->getSupplier() instanceof SupplierInterface);
            }
            self::assertEquals($options['supplier']['name'], $instance->getSupplier()->getName());
            self::assertEquals($options['supplier']['phone'], $instance->getSupplier()->getPhone());
            self::assertEquals($options['supplier']['inn'], $instance->getSupplier()->getInn());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMarkCodeInfo(array $options): void
    {
        $instance = new ReceiptResponseItem($options);

        if (empty($options['mark_code_info'])) {
            self::assertNull($instance->getMarkCodeInfo());
        } else {
            self::assertNotNull($instance->getMarkCodeInfo());
            if (!is_object($instance->getMarkCodeInfo())) {
                self::assertEquals($options['mark_code_info'], $instance->getMarkCodeInfo()->toArray());
            } else {
                self::assertTrue($instance->getMarkCodeInfo() instanceof MarkCodeInfo);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMarkMode(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setMarkMode(null);
        self::assertNull($instance->getMarkMode());

        $instance->setMarkMode($options['mark_mode']);
        if (is_null($options['mark_mode'])) {
            self::assertNull($instance->getMarkMode());
        } else {
            self::assertNotNull($instance->getMarkMode());
            self::assertEquals($options['mark_mode'], $instance->getMarkMode());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMarkQuantity(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        $instance->setMarkQuantity(null);
        self::assertNull($instance->getMarkQuantity());
        if (isset($options['mark_quantity'])) {
            $instance->setMarkQuantity($options['mark_quantity']);
            if (is_array($options['mark_quantity'])) {
                self::assertSame($options['mark_quantity'], $instance->getMarkQuantity()->toArray());
                self::assertSame($options['mark_quantity'], $instance->mark_quantity->toArray());
                self::assertSame($options['mark_quantity'], $instance->markQuantity->toArray());
            } else {
                self::assertNotNull($instance->getMarkQuantity());
                self::assertEquals($options['mark_quantity'], $instance->getMarkQuantity());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetPrice(array $options): void
    {
        $instance = new ReceiptResponseItem($options);

        if (empty($options['amount'])) {
            self::assertNull($instance->getPrice());
        } else {
            self::assertNotNull($instance->getPrice());
            if (!is_object($instance->getPrice())) {
                self::assertEquals($options['amount'], $instance->getPrice()->jsonSerialize());
            } else {
                self::assertTrue($instance->getPrice() instanceof AmountInterface);
            }
            self::assertEquals($options['amount']['value'], $instance->getPrice()->getValue());
            self::assertEquals($options['amount']['currency'], $instance->getPrice()->getCurrency());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetQuantity(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertNotNull($instance->getQuantity());
        self::assertEquals($options['quantity'], $instance->getQuantity());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetPaymentMode(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['payment_mode'], $instance->getPaymentMode());
    }

    public function testSetPaymentSubjectData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setPaymentSubject(null);
        self::assertNull($instance->getPaymentSubject());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetPaymentSubject(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['payment_subject'], $instance->getPaymentSubject());
    }

    public function testSetPaymentModeData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setPaymentMode(null);
        self::assertNull($instance->getPaymentMode());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMeasure(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['measure'], $instance->getMeasure());
    }

    public function testSetMeasureData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setMeasure(null);
        self::assertNull($instance->getMeasure());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetCountryOfOriginCode(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        if (!empty($options['country_of_origin_code'])) {
            self::assertEquals($options['country_of_origin_code'], $instance->getCountryOfOriginCode());
        } else {
            self::assertNull($instance->getCountryOfOriginCode());
        }
    }

    public function testSetCountryOfOriginCodeData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setCountryOfOriginCode(null);
        self::assertNull($instance->getCountryOfOriginCode());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetCustomsDeclarationNumber(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        if (!empty($options['customs_declaration_number'])) {
            self::assertEquals($options['customs_declaration_number'], $instance->getCustomsDeclarationNumber());
        } else {
            self::assertNull($instance->getCustomsDeclarationNumber());
        }
    }

    public function testSetCustomsDeclarationNumberData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setCustomsDeclarationNumber(null);
        self::assertNull($instance->getCustomsDeclarationNumber());
    }

    /**
     * @dataProvider invalidCountryOfOriginCodeDataProvider
     */
    public function testSetCountryOfOriginCodeInvalidData(mixed $options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setCountryOfOriginCode($options);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetExcise(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        if (!empty($options['excise'])) {
            self::assertEquals($options['excise'], $instance->getExcise());
        } else {
            self::assertNull($instance->getExcise());
        }
    }

    public function testSetExciseData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setExcise(null);
        self::assertNull($instance->getExcise());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetProductCode($options): void
    {
        $instance = new ReceiptResponseItem($options);
        if (empty($options['product_code'])) {
            self::assertNull($instance->getProductCode());
            self::assertNull($instance->productCode);
            self::assertNull($instance->product_code);
        } elseif ($options['product_code'] instanceof ProductCode) {
            self::assertEquals((string) $options['product_code'], $instance->getProductCode());
            self::assertEquals((string) $options['product_code'], $instance->productCode);
            self::assertEquals((string) $options['product_code'], $instance->product_code);
        } else {
            self::assertEquals($options['product_code'], (string) $instance->getProductCode());
            self::assertEquals($options['product_code'], (string) $instance->productCode);
            self::assertEquals($options['product_code'], (string) $instance->product_code);
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetPaymentSubjectIndustryDetails(array $options): void
    {
        $instance = new ReceiptResponseItem($options);

        $instance->setPaymentSubjectIndustryDetails($options['payment_subject_industry_details']);

        if (is_array($options['payment_subject_industry_details'])) {
            self::assertEquals($options['payment_subject_industry_details'], $instance->getPaymentSubjectIndustryDetails()->toArray());
            self::assertEquals($options['payment_subject_industry_details'], $instance->payment_subject_industry_details->toArray());
            self::assertEquals($options['payment_subject_industry_details'], $instance->paymentSubjectIndustryDetails->toArray());
        } else {
            self::assertTrue($instance->getPaymentSubjectIndustryDetails()->isEmpty());
            self::assertTrue($instance->payment_subject_industry_details->isEmpty());
            self::assertTrue($instance->paymentSubjectIndustryDetails->isEmpty());
        }
    }

    public function testSetAgentTypeData(): void
    {
        $instance = new ReceiptResponseItem();
        $instance->setAgentType(null);
        self::assertNull($instance->getAgentType());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetAgentType(array $options): void
    {
        $instance = new ReceiptResponseItem($options);
        self::assertEquals($options['agent_type'], $instance->getAgentType());
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     *
     * @param mixed $options
     */
    public function testSetDescriptionInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setDescription($options);
    }

    /**
     * @dataProvider invalidQuantityDataProvider
     *
     * @param mixed $options
     */
    public function testSetQuantityInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setQuantity($options);
    }

    /**
     * @dataProvider invalidMeasureDataProvider
     *
     * @param mixed $options
     */
    public function testSetMeasureInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setMeasure($options);
    }

    /**
     * @dataProvider invalidVatCodeDataProvider
     *
     * @param mixed $options
     */
    public function testSetVatCodeInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setVatCode($options);
    }

    /**
     * @dataProvider invalidExciseDataProvider
     *
     * @param mixed $options
     */
    public function testSetExciseInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setExcise($options);
    }

    /**
     * @dataProvider invalidProductCodeDataProvider
     *
     * @param mixed $options
     */
    public function testSetProductCodeInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setProductCode($options);
    }

    /**
     * @dataProvider invalidSupplierDataProvider
     *
     * @param mixed $options
     */
    public function testSetSupplierInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setSupplier($options);
    }

    /**
     * @dataProvider invalidAgentTypeDataProvider
     *
     * @param mixed $options
     */
    public function testSetAgentTypeInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setAgentType($options);
    }

    /**
     * @dataProvider invalidMarkCodeInfoDataProvider
     *
     * @param mixed $options
     */
    public function testSetMarkCodeInfoInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setMarkCodeInfo($options);
    }

    /**
     * @dataProvider invalidMarkQuantityDataProvider
     *
     * @param mixed $options
     */
    public function testSetMarkQuantityInvalidData($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setMarkQuantity($options);
    }

    /**
     * @dataProvider invalidCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCustomsDeclarationNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->customsDeclarationNumber = $value;
    }

    /**
     * @dataProvider invalidCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCustomsDeclarationNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptResponseItem();
        $instance->setCustomsDeclarationNumber($value);
    }

    public static function validDataProvider()
    {
        $result = [
            [
                [
                    'description' => Random::str(128),
                    'quantity' => Random::float(0.0001, 99.99),
                    'amount' => new MonetaryAmount(Random::int(1, 1000)),
                    'vat_code' => Random::int(1, 6),
                    'measure' => null,
                    'excise' => null,
                    'payment_mode' => null,
                    'payment_subject' => null,
                    'product_code' => null,
                    'mark_code_info' => null,
                    'mark_mode' => null,
                    'mark_quantity' => null,
                    'supplier' => new Supplier([
                        'name' => Random::str(128),
                        'phone' => Random::str(4, 12, '1234567890'),
                        'inn' => '1000000000',
                    ]),
                    'agent_type' => null,
                    'payment_subject_industry_details' => null,
                ],
            ],
        ];

        for ($i = 0; $i < 9; $i++) {
            $test = [
                [
                    'description' => Random::str(128),
                    'quantity' => Random::float(0.0001, 99.99),
                    'measure' => Random::value(ReceiptItemMeasure::getValidValues()),
                    'amount' => [
                        'value' => round(Random::float(0.1, 99.99), 2),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                    'vat_code' => Random::int(1, 6),
                    'excise' => round(Random::float(1.0, 10.0), 2),
                    'payment_mode' => Random::value(PaymentMode::getValidValues()),
                    'payment_subject' => Random::value(PaymentSubject::getValidValues()),
                    'product_code' => Random::value([
                        null,
                        Random::str(2, 96, '0123456789ABCDEF '),
                        new ProductCode('010463003407001221SxMGorvNuq6Wk91fgr92sdfsdfghfgjh'),
                    ]),
                    'country_of_origin_code' => Random::value(['RU', 'US', 'CN']),
                    'customs_declaration_number' => Random::value([
                        null,
                        '',
                        Random::str(2),
                        Random::str(2, 31),
                        Random::str(32),
                    ]),
                    'mark_code_info' => [
                        'mark_code_raw' => '010460406000590021N4N57RTCBUZTQ\u001d2403054002410161218\u001d1424010191ffd0\u001g92tIAF/YVpU4roQS3M/m4z78yFq0nc/WsSmLeX6QkF/YVWwy5IMYAeiQ91Xa2m/fFSJcOkb2N+uUUtfr4n0mOX0Q==',
                    ],
                    'mark_mode' => Random::value([null, 0, '0']),
                    'payment_subject_industry_details' => [
                        [
                            'federal_id' => '001',
                            'document_date' => date('Y-m-d', Random::int(100000000, 200000000)),
                            'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                            'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                        ],
                    ],
                    'supplier' => [
                        'name' => Random::str(128),
                        'phone' => Random::str(4, 12, '1234567890'),
                        'inn' => '1000000000',
                    ],
                    'agent_type' => Random::value(AgentType::getValidValues()),
                ],
            ];
            if (ReceiptItemMeasure::PIECE === $test[0]['measure']) {
                $test[0]['mark_quantity'] = [
                    'numerator' => Random::int(1, 100),
                    'denominator' => 100,
                ];
            }
            $result[] = $test;
        }

        return $result;
    }

    public static function invalidDescriptionDataProvider()
    {
        return [
            [''],
        ];
    }

    public static function invalidQuantityDataProvider()
    {
        return [
            [null],
            [0.0],
        ];
    }

    public static function invalidMeasureDataProvider()
    {
        return [
            [true],
            [Random::str(10)],
        ];
    }

    public static function invalidVatCodeDataProvider()
    {
        return [
            [0.0],
        ];
    }

    public static function invalidExciseDataProvider()
    {
        return [
            [-Random::float(10)],
        ];
    }

    public static function invalidProductCodeDataProvider()
    {
        return [
            [new StringObject('')],
            [true],
            [false],
            [new stdClass()],
            [Random::str(2, 96, 'GHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=`~?><:"\'')],
            [Random::str(97, 100, '0123456789ABCDEF ')],
        ];
    }

    public static function invalidPaymentDataProvider()
    {
        return [
            [new Airline()],
        ];
    }

    public static function invalidSupplierDataProvider()
    {
        return [
            [Random::int()],
            [Random::str(5)],
            [new Airline()],
        ];
    }

    public static function invalidCountryOfOriginCodeDataProvider()
    {
        return [
            [Random::str(2, 2, '0123456789!@#$%^&*()_+-=')],
            [Random::str(3, 10)],
        ];
    }

    public static function invalidAgentTypeDataProvider()
    {
        return [
            [Random::str(1, 10)],
        ];
    }

    public static function invalidMarkQuantityDataProvider()
    {
        return [
            [1.0],
            [1],
            [true],
            [new stdClass()],
        ];
    }

    public static function invalidMarkCodeInfoDataProvider()
    {
        return [
            [1.0],
            [1],
            [true],
            [new stdClass()],
            [Random::str(1, 10)],
        ];
    }

    public static function invalidCustomsDeclarationNumberDataProvider()
    {
        return [
            [Random::str(33, 64)],
        ];
    }

    public function invalidPaymentSubjectIndustryDetailsDataProvider()
    {
        return [
            [1.0],
            [1],
            [true],
            [new stdClass()],
            [[new stdClass()]],
        ];
    }
}
