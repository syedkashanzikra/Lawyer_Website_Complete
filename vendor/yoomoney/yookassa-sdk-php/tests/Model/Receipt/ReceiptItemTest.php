<?php

namespace Tests\YooKassa\Model\Receipt;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;
use YooKassa\Helpers\ProductCode;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Receipt\AgentType;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\MarkCodeInfo;
use YooKassa\Model\Receipt\MarkQuantity;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Model\Receipt\ReceiptItemMeasure;
use YooKassa\Model\Receipt\Supplier;

/**
 * @internal
 */
class ReceiptItemTest extends TestCase
{
    /**
     * @dataProvider validDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetDescription($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setDescription($value);
        self::assertEquals((string) $value, $instance->getDescription());
        self::assertEquals((string) $value, $instance->description);
    }

    /**
     * @dataProvider validDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetterDescription($value): void
    {
        $instance = $this->getTestInstance();
        $instance->description = $value;
        self::assertEquals((string) $value, $instance->getDescription());
        self::assertEquals((string) $value, $instance->description);
    }

    public static function validDescriptionDataProvider()
    {
        return [
            [Random::str(1)],
            [Random::str(2, 31)],
            [Random::str(32)],
            [new StringObject(Random::str(64))],
            [123],
            [45.3],
        ];
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDescription($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setDescription($value);
    }

    /**
     * @dataProvider invalidDescriptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidDescription($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->description = $value;
    }

    public static function invalidDescriptionDataProvider()
    {
        return [
            [null],
            [''],
            [new StringObject('')],
            [false],
        ];
    }

    /**
     * @dataProvider validQuantityDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetQuantity($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setQuantity($value);
        self::assertEquals((float) $value, $instance->getQuantity());
        self::assertEquals((float) $value, $instance->quantity);
    }

    /**
     * @dataProvider validQuantityDataProvider
     *
     * @param mixed $value
     */
    public function testSetterQuantity($value): void
    {
        $instance = $this->getTestInstance();

        $instance->quantity = $value;
        self::assertEquals((float) $value, $instance->getQuantity());
        self::assertEquals((float) $value, $instance->quantity);
    }

    public static function validQuantityDataProvider()
    {
        return [
            [1],
            [1.3],
            [0.001],
            [10000.001],
            ['3.1415'],
            [Random::float(0.001, 9999.999)],
            [Random::int(1, 9999)],
        ];
    }

    /**
     * @dataProvider invalidQuantityDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidQuantity($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setQuantity($value);
    }

    /**
     * @dataProvider invalidQuantityDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidQuantity($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->quantity = $value;
    }

    public static function invalidQuantityDataProvider()
    {
        return [
            [null],
            [0.0],
            [Random::float(-100, -0.001)],
        ];
    }

    /**
     * @dataProvider validVatCodeDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetVatCode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setVatCode($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getVatCode());
            self::assertNull($instance->vatCode);
            self::assertNull($instance->vat_code);
        } else {
            self::assertEquals((int) $value, $instance->getVatCode());
            self::assertEquals((int) $value, $instance->vatCode);
            self::assertEquals((int) $value, $instance->vat_code);
        }
    }

    /**
     * @dataProvider validVatCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterVatCode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->vatCode = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getVatCode());
            self::assertNull($instance->vatCode);
            self::assertNull($instance->vat_code);
        } else {
            self::assertEquals((int) $value, $instance->getVatCode());
            self::assertEquals((int) $value, $instance->vatCode);
            self::assertEquals((int) $value, $instance->vat_code);
        }
    }

    /**
     * @dataProvider validDataAgentType
     *
     * @param mixed $value
     */
    public function testSetAgentType($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAgentType($value);
        self::assertSame($value, $instance->getAgentType());
    }

    public static function validDataAgentType()
    {
        $values = [
            [null],
        ];
        for ($i = 0; $i < 5; $i++) {
            $values[] = [Random::value(AgentType::getValidValues())];
        }

        return $values;
    }

    /**
     * @dataProvider invalidAgentTypeDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidAgentType($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setAgentType($value);
    }

    /**
     * @dataProvider invalidAgentTypeDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetterInvalidAgentType($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->agent_type = $value;
    }

    public static function invalidAgentTypeDataProvider()
    {
        return [
            [Random::str(10), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validDataSupplier
     *
     * @param mixed $value
     */
    public function testSetSupplier($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setSupplier($value);
        if (is_array($value)) {
            self::assertEquals($value, $instance->getSupplier()->toArray());
        } else {
            self::assertEquals($value, $instance->getSupplier());
        }
    }

    /**
     * @dataProvider invalidSupplierDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidSupplier($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setSupplier($value);
    }

    /**
     * @dataProvider invalidSupplierDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetterInvalidSupplier($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->supplier = $value;
    }

    public static function invalidSupplierDataProvider()
    {
        return [
            [1, InvalidPropertyValueTypeException::class],
            [Random::str(10), InvalidPropertyValueTypeException::class],
            [true, InvalidPropertyValueTypeException::class],
            [new stdClass(), InvalidPropertyValueTypeException::class],
        ];
    }

    /**
     * @return array[]
     *
     * @throws Exception
     */
    public static function validDataSupplier(): array
    {
        $validData = [
            [null],
            [
                [
                    'name' => Random::str(1, 100),
                    'phone' => '79000000000',
                    'inn' => '1000000000',
                ],
            ],
        ];
        for ($i = 0; $i < 3; $i++) {
            $supplier = [
                new Supplier(
                    [
                        'name' => Random::str(1, 100),
                        'phone' => '79000000000',
                        'inn' => '1000000000',
                    ]
                ),
            ];
            $validData[] = $supplier;
        }

        return $validData;
    }

    /**
     * @dataProvider validVatCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterSnakeVatCode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->vat_code = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getVatCode());
            self::assertNull($instance->vatCode);
            self::assertNull($instance->vat_code);
        } else {
            self::assertEquals((int) $value, $instance->getVatCode());
            self::assertEquals((int) $value, $instance->vatCode);
            self::assertEquals((int) $value, $instance->vat_code);
        }
    }

    /**
     * @dataProvider validPaymentSubjectDataProvider
     *
     * @param mixed $value
     */
    public function testSetterPaymentSubject($value): void
    {
        $instance = $this->getTestInstance();

        $instance->payment_subject = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentSubject());
            self::assertNull($instance->payment_subject);
            self::assertNull($instance->paymentSubject);
        } else {
            self::assertContains($instance->getPaymentSubject(), PaymentSubject::getValidValues());
            self::assertContains($instance->payment_subject, PaymentSubject::getValidValues());
            self::assertContains($instance->paymentSubject, PaymentSubject::getValidValues());
        }
    }

    /**
     * @dataProvider validPaymentSubjectDataProvider
     *
     * @param mixed $value
     */
    public function testSetterSnakePaymentSubject($value): void
    {
        $instance = $this->getTestInstance();

        $instance->paymentSubject = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentSubject());
            self::assertNull($instance->payment_subject);
            self::assertNull($instance->paymentSubject);
        } else {
            self::assertContains($instance->getPaymentSubject(), PaymentSubject::getValidValues());
            self::assertContains($instance->payment_subject, PaymentSubject::getValidValues());
            self::assertContains($instance->paymentSubject, PaymentSubject::getValidValues());
        }
    }

    /**
     * @dataProvider validPaymentModeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterPaymentMode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->payment_mode = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentMode());
            self::assertNull($instance->payment_mode);
            self::assertNull($instance->paymentMode);
        } else {
            self::assertContains($instance->getPaymentMode(), PaymentMode::getValidValues());
            self::assertContains($instance->payment_mode, PaymentMode::getValidValues());
            self::assertContains($instance->paymentMode, PaymentMode::getValidValues());
        }
    }

    /**
     * @dataProvider validPaymentModeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterSnakePaymentMode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->paymentMode = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPaymentMode());
            self::assertNull($instance->payment_mode);
            self::assertNull($instance->paymentMode);
        } else {
            self::assertContains($instance->getPaymentMode(), PaymentMode::getValidValues());
            self::assertContains($instance->payment_mode, PaymentMode::getValidValues());
            self::assertContains($instance->paymentMode, PaymentMode::getValidValues());
        }
    }

    public static function validVatCodeDataProvider()
    {
        return [
            [1],
            [2],
            [3],
            [4],
            [5],
            [6],
        ];
    }

    public static function validPaymentSubjectDataProvider()
    {
        return [
            [null],
            [''],
            [PaymentSubject::ANOTHER],
            [PaymentSubject::AGENT_COMMISSION],
            [PaymentSubject::PAYMENT],
            [PaymentSubject::GAMBLING_PRIZE],
            [PaymentSubject::GAMBLING_BET],
            [PaymentSubject::COMPOSITE],
            [PaymentSubject::INTELLECTUAL_ACTIVITY],
            [PaymentSubject::LOTTERY_PRIZE],
            [PaymentSubject::LOTTERY],
            [PaymentSubject::SERVICE],
            [PaymentSubject::JOB],
            [PaymentSubject::EXCISE],
            [PaymentSubject::COMMODITY],
        ];
    }

    public static function validPaymentModeDataProvider()
    {
        return [
            [null],
            [''],
            [PaymentMode::ADVANCE],
            [PaymentMode::CREDIT],
            [PaymentMode::CREDIT_PAYMENT],
            [PaymentMode::FULL_PAYMENT],
            [PaymentMode::FULL_PREPAYMENT],
            [PaymentMode::PARTIAL_PAYMENT],
            [PaymentMode::PARTIAL_PREPAYMENT],
        ];
    }

    /**
     * @dataProvider invalidVatCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidVatCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setVatCode($value);
    }

    /**
     * @dataProvider invalidVatCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidVatCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->vatCode = $value;
    }

    /**
     * @dataProvider invalidVatCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeVatCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->vat_code = $value;
    }

    public static function invalidVatCodeDataProvider()
    {
        return [
            [0],
            [7],
            [Random::int(-100, -1)],
            [Random::int(8, 100)],
        ];
    }

    /**
     * @dataProvider validPriceDataProvider
     */
    public function testGetSetPrice(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setPrice($value);
        if (is_array($value)) {
            self::assertSame($value, $instance->getPrice()->toArray());
            self::assertSame($value, $instance->price->toArray());
        } else {
            self::assertSame($value, $instance->getPrice());
            self::assertSame($value, $instance->price);
        }
    }

    /**
     * @dataProvider validPriceDataProvider
     */
    public function testSetterPrice(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->price = $value;
        if (is_array($value)) {
            self::assertSame($value, $instance->getPrice()->toArray());
            self::assertSame($value, $instance->price->toArray());
        } else {
            self::assertSame($value, $instance->getPrice());
            self::assertSame($value, $instance->price);
        }
    }

    public static function validPriceDataProvider()
    {
        return [
            [
                [
                    'value' => number_format(Random::float(1, 100), 2, '.', ''),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
            ],
            [
                new ReceiptItemAmount([
                    'value' => number_format(Random::float(1, 100), 2, '.', ''),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ]),
            ],
            [
                new ReceiptItemAmount(
                    number_format(Random::float(1, 100), 2, '.', ''),
                    Random::value(CurrencyCode::getValidValues())
                ),
            ],
            [
                new ReceiptItemAmount(),
            ],
        ];
    }

    /**
     * @dataProvider invalidPriceDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPrice($value, string $exceptionType): void
    {
        $this->expectException($exceptionType);
        $this->getTestInstance()->setPrice($value);
    }

    /**
     * @dataProvider invalidPriceDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPrice($value, string $exceptionType): void
    {
        $this->expectException($exceptionType);
        $this->getTestInstance()->price = $value;
    }

    public static function invalidPriceDataProvider()
    {
        return [
            [null, TypeError::class],
            ['', TypeError::class],
            [1.0, TypeError::class],
            [1, TypeError::class],
            [true, TypeError::class],
            [false, TypeError::class],
            [new stdClass(), TypeError::class],
        ];
    }

    /**
     * @dataProvider validIsShippingDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetIsShipping($value): void
    {
        $instance = $this->getTestInstance();

        self::assertFalse($instance->isShipping());
        $instance->setIsShipping($value);
        if ($value) {
            self::assertTrue($instance->isShipping());
        } else {
            self::assertFalse($instance->isShipping());
        }
    }

    /**
     * @dataProvider validIsShippingDataProvider
     *
     * @param mixed $value
     */
    public function testSetterIsShipping($value): void
    {
        $instance = $this->getTestInstance();

        $instance->isShipping = $value;
        if ($value) {
            self::assertTrue($instance->isShipping());
        } else {
            self::assertFalse($instance->isShipping());
        }
    }

    public static function validIsShippingDataProvider(): array
    {
        return [
            [true],
            [false],
            [0],
            [1],
            [2],
            [''],
        ];
    }

    /**
     * @dataProvider validApplyDiscountCoefficientDataProvider
     *
     * @param mixed $baseValue
     * @param mixed $coefficient
     * @param mixed $expected
     */
    public function testApplyDiscountCoefficient($baseValue, $coefficient, $expected): void
    {
        $instance = $this->getTestInstance();

        $instance->setPrice(new ReceiptItemAmount($baseValue));
        $instance->applyDiscountCoefficient($coefficient);
        self::assertEquals($expected, $instance->getPrice()->getIntegerValue());
    }

    public static function validApplyDiscountCoefficientDataProvider()
    {
        return [
            [1, 1, 100],
            [1.01, 1, 101],
            [1.01, 0.5, 51],
            [1.01, 0.4, 40],
            [1.00, 0.5, 50],
            [1.00, 0.333333333, 33],
            [2.00, 0.333333333, 67],
        ];
    }

    /**
     * @dataProvider invalidApplyDiscountCoefficientDataProvider
     *
     * @param mixed $coefficient
     */
    public function testInvalidApplyDiscountCoefficient($coefficient): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();

        $instance->setPrice(new ReceiptItemAmount(Random::int(100)));
        $instance->applyDiscountCoefficient($coefficient);
    }

    public static function invalidApplyDiscountCoefficientDataProvider()
    {
        return [
            [null],
            [-1.4],
            [-0.01],
            [-0.0001],
            [0.0],
            [false],
        ];
    }

    /**
     * @dataProvider validAmountDataProvider
     *
     * @param mixed $price
     * @param mixed $quantity
     */
    public function testGetAmount($price, $quantity): void
    {
        $instance = $this->getTestInstance();
        $instance->setPrice(new ReceiptItemAmount($price));
        $instance->setQuantity($quantity);
        $expected = (int) round($price * 100.0 * $quantity);
        self::assertEquals($expected, $instance->getAmount());
    }

    public static function validAmountDataProvider()
    {
        return [
            [1, 1],
            [1.01, 1.01],
        ];
    }

    /**
     * @dataProvider validIncreasePriceDataProvider
     */
    public function testIncreasePrice(float $price, float $value, int $expected): void
    {
        $instance = $this->getTestInstance();
        $instance->setPrice(new ReceiptItemAmount($price));
        $instance->increasePrice($value);
        self::assertEquals($expected, $instance->getPrice()->getIntegerValue());
    }

    public static function validIncreasePriceDataProvider()
    {
        return [
            [1, 1, 200],
            [1.01, 3.03, 404],
            [1.01, -0.01, 100],
        ];
    }

    /**
     * @dataProvider invalidIncreasePriceDataProvider
     */
    public function testInvalidIncreasePrice(mixed $price, mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setPrice(new ReceiptItemAmount($price));
        $instance->increasePrice($value);
    }

    public static function invalidIncreasePriceDataProvider()
    {
        return [
            [1, -1],
            [1.01, -1.01],
            [1.01, -1.02],
        ];
    }

    /**
     * @dataProvider validFetchItemDataProvider
     *
     * @param mixed $price
     * @param mixed $quantity
     * @param mixed $fetch
     */
    public function testFetchItem($price, $quantity, $fetch): void
    {
        $instance = $this->getTestInstance();
        $instance->setPrice(new ReceiptItemAmount($price));
        $instance->setQuantity($quantity);

        $fetched = $instance->fetchItem($fetch);
        self::assertInstanceOf(ReceiptItem::class, $fetched);
        self::assertNotSame($fetched->getPrice(), $instance->getPrice());
        self::assertEquals($fetch, $fetched->getQuantity());
        self::assertEquals($quantity - $fetch, $instance->getQuantity());
        self::assertEquals($price, $instance->getPrice()->getValue());
        self::assertEquals($price, $fetched->getPrice()->getValue());
    }

    public static function validFetchItemDataProvider()
    {
        return [
            [1, 2, 1],
            [1.01, 2, 1.5],
            [1.01, 2, 1.99],
            [1.01, 2, 1.9999],
        ];
    }

    /**
     * @dataProvider invalidFetchItemDataProvider
     *
     * @param mixed $quantity
     * @param mixed $fetch
     */
    public function testInvalidFetchItem($quantity, $fetch): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setPrice(new ReceiptItemAmount(Random::int(1, 100)));
        $instance->setQuantity($quantity);
        $instance->fetchItem($fetch);
    }

    public static function invalidFetchItemDataProvider()
    {
        return [
            [1, 1],
            [1.01, 1.01],
            [1.01, 1.02],
            [1, null],
            [1, 0.0],
            [1, -12.3],
        ];
    }

    /**
     * @dataProvider validProductCodeDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetProductCode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setProductCode($value);
        self::assertEquals((string) $value, $instance->getProductCode());
        self::assertEquals((string) $value, $instance->productCode);
        self::assertEquals((string) $value, $instance->product_code);
    }

    /**
     * @dataProvider validProductCodeDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetSnakeProductCode($value): void
    {
        $instance = $this->getTestInstance();
        $instance->product_code = $value;
        self::assertEquals((string) $value, $instance->getProductCode());
        self::assertEquals((string) $value, $instance->productCode);
        self::assertEquals((string) $value, $instance->product_code);
    }

    /**
     * @dataProvider validProductCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterProductCode($value): void
    {
        $instance = $this->getTestInstance();
        $instance->productCode = $value;
        self::assertEquals((string) $value, $instance->getProductCode());
        self::assertEquals((string) $value, $instance->productCode);
        self::assertEquals((string) $value, $instance->product_code);
    }

    public static function validProductCodeDataProvider()
    {
        return [
            [null],
            [''],
            [Random::str(2, 96, '0123456789ABCDEF ')],
            [new ProductCode('010463003407001221SxMGorvNuq6Wk91fgr92sdfsdfghfgjh')],
        ];
    }

    /**
     * @dataProvider invalidProductCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidProductCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setProductCode($value);
    }

    /**
     * @dataProvider invalidProductCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidProductCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->productCode = $value;
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

    /**
     * @dataProvider validCountryOfOriginCodeDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCountryOfOriginCode($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setCountryOfOriginCode($value);
        self::assertEquals((string) $value, $instance->getCountryOfOriginCode());
        self::assertEquals((string) $value, $instance->countryOfOriginCode);
        self::assertEquals((string) $value, $instance->country_of_origin_code);
    }

    /**
     * @dataProvider validCountryOfOriginCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterSnakeCountryOfOriginCode($value): void
    {
        $instance = $this->getTestInstance();
        $instance->country_of_origin_code = $value;
        self::assertEquals((string) $value, $instance->getCountryOfOriginCode());
        self::assertEquals((string) $value, $instance->countryOfOriginCode);
        self::assertEquals((string) $value, $instance->country_of_origin_code);
    }

    /**
     * @dataProvider validCountryOfOriginCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterCountryOfOriginCode($value): void
    {
        $instance = $this->getTestInstance();
        $instance->countryOfOriginCode = $value;
        self::assertEquals((string) $value, $instance->getCountryOfOriginCode());
        self::assertEquals((string) $value, $instance->countryOfOriginCode);
        self::assertEquals((string) $value, $instance->country_of_origin_code);
    }

    public static function validCountryOfOriginCodeDataProvider()
    {
        return [
            [null],
            [''],
            [Random::str(2, 2, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')],
        ];
    }

    /**
     * @dataProvider invalidCountryOfOriginCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCountryOfOriginCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCountryOfOriginCode($value);
    }

    /**
     * @dataProvider invalidCountryOfOriginCodeDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCountryOfOriginCode($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->countryOfOriginCode = $value;
    }

    public static function invalidCountryOfOriginCodeDataProvider()
    {
        return [
            [true],
            [Random::int()],
            [Random::str(1, 1)],
            [Random::str(3, null, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')],
            [Random::str(2, 2, '0123456789!@#$%^&*()_+-=`~?><:"\' ')],
        ];
    }

    /**
     * @dataProvider validCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetCustomsDeclarationNumber($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setCustomsDeclarationNumber($value);
        self::assertEquals((string) $value, $instance->getCustomsDeclarationNumber());
        self::assertEquals((string) $value, $instance->customsDeclarationNumber);
        self::assertEquals((string) $value, $instance->customs_declaration_number);
    }

    /**
     * @dataProvider validCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterCustomsDeclarationNumber($value): void
    {
        $instance = $this->getTestInstance();
        $instance->customsDeclarationNumber = $value;
        self::assertEquals((string) $value, $instance->getCustomsDeclarationNumber());
        self::assertEquals((string) $value, $instance->customsDeclarationNumber);
        self::assertEquals((string) $value, $instance->customs_declaration_number);
    }

    /**
     * @dataProvider validCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterSnakeCustomsDeclarationNumber($value): void
    {
        $instance = $this->getTestInstance();
        $instance->customs_declaration_number = $value;
        self::assertEquals((string) $value, $instance->getCustomsDeclarationNumber());
        self::assertEquals((string) $value, $instance->customsDeclarationNumber);
        self::assertEquals((string) $value, $instance->customs_declaration_number);
    }

    public static function validCustomsDeclarationNumberDataProvider()
    {
        return [
            [null],
            [''],
            [Random::str(1)],
            [Random::str(2, 31)],
            [Random::str(32)],
        ];
    }

    /**
     * @dataProvider invalidCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCustomsDeclarationNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCustomsDeclarationNumber($value);
    }

    /**
     * @dataProvider invalidCustomsDeclarationNumberDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCustomsDeclarationNumber($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->customsDeclarationNumber = $value;
    }

    public static function invalidCustomsDeclarationNumberDataProvider()
    {
        return [
            [Random::str(33, 64)],
        ];
    }

    /**
     * @dataProvider validExciseDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetExcise($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setExcise($value);
        self::assertEquals((float) $value, $instance->getExcise());
        self::assertEquals((float) $value, $instance->excise);
    }

    /**
     * @dataProvider validExciseDataProvider
     *
     * @param mixed $value
     */
    public function testSetterExcise($value): void
    {
        $instance = $this->getTestInstance();

        $instance->excise = $value;
        self::assertEquals((float) $value, $instance->getExcise());
        self::assertEquals((float) $value, $instance->excise);
    }

    public static function validExciseDataProvider()
    {
        return [
            [null],
            [1],
            [1.3],
            [0.001],
            [10000.001],
            ['3.1415'],
            [Random::float(0.001, 9999.999)],
            [Random::int(1, 9999)],
        ];
    }

    /**
     * @dataProvider invalidExciseDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidExcise($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setExcise($value);
    }

    /**
     * @dataProvider invalidExciseDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidExcise($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->excise = $value;
    }

    public static function invalidExciseDataProvider()
    {
        return [
            [0.0],
            [Random::float(-100, -0.001)],
        ];
    }

    /**
     * @dataProvider validMarkCodeInfoDataProvider
     *
     * @param array|MarkCodeInfo $value
     */
    public function testGetSetMarkCodeInfo($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setMarkCodeInfo($value);
        if (is_array($value)) {
            self::assertSame($value, $instance->getMarkCodeInfo()->toArray());
            self::assertSame($value, $instance->mark_code_info->toArray());
        } else {
            self::assertSame($value, $instance->getMarkCodeInfo());
            self::assertSame($value, $instance->mark_code_info);
        }
    }

    /**
     * @dataProvider validMarkCodeInfoDataProvider
     *
     * @param array|MarkCodeInfo $value
     */
    public function testSetterMarkCodeInfo($value): void
    {
        $instance = $this->getTestInstance();
        $instance->mark_code_info = $value;
        if (is_array($value)) {
            self::assertSame($value, $instance->getMarkCodeInfo()->toArray());
            self::assertSame($value, $instance->mark_code_info->toArray());
        } else {
            self::assertSame($value, $instance->getMarkCodeInfo());
            self::assertSame($value, $instance->mark_code_info);
        }
    }

    public static function validMarkCodeInfoDataProvider()
    {
        return [
            [
                new MarkCodeInfo([
                    'mark_code_raw' => '010460406000590021N4N57RTCBUZTQ\u001d2403054002410161218\u001d1424010191ffd0\u001g92tIAF/YVpU4roQS3M/m4z78yFq0nc/WsSmLeX6QkF/YVWwy5IMYAeiQ91Xa2m/fFSJcOkb2N+uUUtfr4n0mOX0Q==',
                ]),
            ],
            [
                [
                    'mark_code_raw' => '010460406000590021N4N57RTCBUZTQ\u001d2403054002410161218\u001d1424010191ffd0\u001g92tIAF/YVpU4roQS3M/m4z78yFq0nc/WsSmLeX6QkF/YVWwy5IMYAeiQ91Xa2m/fFSJcOkb2N+uUUtfr4n0mOX0Q==',
                ],
            ],
            [
                new MarkCodeInfo(),
            ],
            [null],
        ];
    }

    /**
     * @dataProvider invalidMarkCodeInfoDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidMarkCodeInfo($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setMarkCodeInfo($value);
    }

    /**
     * @dataProvider invalidMarkCodeInfoDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetterInvalidMarkCodeInfo($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->mark_code_info = $value;
    }

    public static function invalidMarkCodeInfoDataProvider()
    {
        return [
            [new stdClass(), InvalidPropertyValueTypeException::class],
        ];
    }

    /**
     * @dataProvider validMarkQuantityDataProvider
     *
     * @param array|MarkQuantity $value
     */
    public function testGetSetMarkQuantity($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setMarkQuantity($value);
        if (is_array($value)) {
            self::assertSame($value, $instance->getMarkQuantity()->toArray());
            self::assertSame($value, $instance->mark_quantity->toArray());
            self::assertSame($value, $instance->markQuantity->toArray());
        } else {
            self::assertSame($value, $instance->getMarkQuantity());
            self::assertSame($value, $instance->mark_quantity);
            self::assertSame($value, $instance->markQuantity);
        }
    }

    /**
     * @dataProvider validMarkQuantityDataProvider
     */
    public function testSetterMarkQuantity(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->mark_quantity = $value;
        if (is_array($value)) {
            self::assertSame($value, $instance->getMarkQuantity()->toArray());
            self::assertSame($value, $instance->mark_quantity->toArray());
            self::assertSame($value, $instance->markQuantity->toArray());
        } else {
            self::assertSame($value, $instance->getMarkQuantity());
            self::assertSame($value, $instance->mark_quantity);
            self::assertSame($value, $instance->markQuantity);
        }
    }

    public static function validMarkQuantityDataProvider()
    {
        return [
            [
                new MarkQuantity([
                    'numerator' => 1,
                    'denominator' => 1,
                ]),
            ],
            [
                [
                    'numerator' => 1,
                    'denominator' => 10,
                ],
            ],
            [null],
        ];
    }

    /**
     * @dataProvider invalidMarkQuantityDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidMarkQuantity($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setMarkQuantity($value);
    }

    /**
     * @dataProvider invalidMarkQuantityDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetterInvalidMarkQuantity($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->mark_quantity = $value;
    }

    public static function invalidMarkQuantityDataProvider()
    {
        return [
            [1.0, InvalidPropertyValueTypeException::class],
            [1, InvalidPropertyValueTypeException::class],
            [true, InvalidPropertyValueTypeException::class],
            [new stdClass(), InvalidPropertyValueTypeException::class],
        ];
    }

    /**
     * @dataProvider validIndustryDetailsDataProvider
     *
     * @param array|IndustryDetails $value
     */
    public function testGetSetPaymentSubjectIndustryDetails($value): void
    {
        $instance = $this->getTestInstance();

        $instance->setPaymentSubjectIndustryDetails($value);

        if (is_array($value)) {
            self::assertCount(count($value), $instance->getPaymentSubjectIndustryDetails());
            self::assertCount(count($value), $instance->payment_subject_industry_details);
            self::assertCount(count($value), $instance->paymentSubjectIndustryDetails);
        } else {
            self::assertSame($value, $instance->getPaymentSubjectIndustryDetails());
            self::assertSame($value, $instance->payment_subject_industry_details);
            self::assertSame($value, $instance->paymentSubjectIndustryDetails);
        }
    }

    /**
     * @dataProvider validIndustryDetailsDataProvider
     */
    public function testSetterPaymentSubjectIndustryDetails(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->payment_subject_industry_details = $value;

        if (is_array($value)) {
            self::assertCount(count($value), $instance->getPaymentSubjectIndustryDetails());
            self::assertCount(count($value), $instance->payment_subject_industry_details);
            self::assertCount(count($value), $instance->paymentSubjectIndustryDetails);
        } else {
            self::assertSame($value, $instance->getPaymentSubjectIndustryDetails());
            self::assertSame($value, $instance->payment_subject_industry_details);
            self::assertSame($value, $instance->paymentSubjectIndustryDetails);
        }
    }

    public static function validIndustryDetailsDataProvider()
    {
        return [
            [
                [
                    [
                        'federal_id' => '001',
                        'document_date' => date('Y-m-d', Random::int(100000000, 200000000)),
                        'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                        'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                    ],
                ],
            ],
            [
                [
                    new IndustryDetails([
                        'federal_id' => '001',
                        'document_date' => date('Y-m-d', Random::int(100000000, 200000000)),
                        'document_number' => Random::str(1, IndustryDetails::DOCUMENT_NUMBER_MAX_LENGTH),
                        'value' => Random::str(1, IndustryDetails::VALUE_MAX_LENGTH),
                    ])
                ]
            ],
        ];
    }

    /**
     * @dataProvider invalidPaymentSubjectIndustryDetailsDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidPaymentSubjectIndustryDetails($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setPaymentSubjectIndustryDetails($value);
    }

    /**
     * @dataProvider invalidPaymentSubjectIndustryDetailsDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetterInvalidPaymentSubjectIndustryDetails($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->payment_subject_industry_details = $value;
    }

    public static function invalidPaymentSubjectIndustryDetailsDataProvider()
    {
        return [
            [1.0, InvalidPropertyValueTypeException::class],
            [1, InvalidPropertyValueTypeException::class],
            [true, InvalidPropertyValueTypeException::class],
            [new stdClass(), InvalidPropertyValueTypeException::class],
            [Random::str(10), InvalidPropertyValueTypeException::class],
        ];
    }

    /**
     * @dataProvider validMeasureDataProvider
     */
    public function testGetSetMeasure(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setMeasure($value);

        self::assertSame($value, $instance->getMeasure());
        self::assertSame($value, $instance->measure);
    }

    /**
     * @dataProvider validMeasureDataProvider
     */
    public function testSetterMeasure(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->measure = $value;

        self::assertSame($value, $instance->getMeasure());
        self::assertSame($value, $instance->measure);
    }

    public static function validMeasureDataProvider()
    {
        $test = [
            [null],
        ];

        for ($i = 0; $i < 5; $i++) {
            $test[] = [Random::value(ReceiptItemMeasure::getValidValues())];
        }

        return $test;
    }

    /**
     * @dataProvider invalidMeasureDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetInvalidMeasure($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setMeasure($value);
    }

    /**
     * @dataProvider invalidMeasureDataProvider
     *
     * @param mixed $value
     * @param mixed $exception
     */
    public function testSetterInvalidMeasure($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->measure = $value;
    }

    public static function invalidMeasureDataProvider()
    {
        return [
            [Random::str(10), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validMarkModeDataProvider
     */
    public function testGetSetMarkMode(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setMarkMode($value);

        self::assertSame($value, $instance->getMarkMode());
        self::assertSame($value, $instance->mark_mode);
    }

    /**
     * @dataProvider validMarkModeDataProvider
     */
    public function testSetterMarkMode(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->mark_mode = $value;

        self::assertSame($value, $instance->getMarkMode());
        self::assertSame($value, $instance->mark_mode);
    }

    public static function validMarkModeDataProvider()
    {
        return [
            [null],
            ['0']
        ];
    }

    /**
     * @dataProvider validAdditionalPaymentSubjectPropsDataProvider
     */
    public function testGetSetAdditionalPaymentSubjectProps(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setAdditionalPaymentSubjectProps($value);

        self::assertSame($value, $instance->getAdditionalPaymentSubjectProps());
        self::assertSame($value, $instance->additional_payment_subject_props);
        self::assertSame($value, $instance->additionalPaymentSubjectProps);
    }

    /**
     * @dataProvider validAdditionalPaymentSubjectPropsDataProvider
     */
    public function testSetterAdditionalPaymentSubjectProps(mixed $value): void
    {
        $instance = $this->getTestInstance();
        $instance->additionalPaymentSubjectProps = $value;

        self::assertSame($value, $instance->getAdditionalPaymentSubjectProps());
        self::assertSame($value, $instance->additional_payment_subject_props);
        self::assertSame($value, $instance->additionalPaymentSubjectProps);
    }

    public static function validAdditionalPaymentSubjectPropsDataProvider()
    {
        return [
            [null],
            ['0'],
            [Random::str(1, ReceiptItem::ADD_PROPS_MAX_LENGTH)],
        ];
    }

    protected function getTestInstance()
    {
        return new ReceiptItem();
    }
}
