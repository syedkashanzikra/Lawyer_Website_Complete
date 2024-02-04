<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Model\Payment\Payment;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\Recipient;
use YooKassa\Model\Receipt\IndustryDetails;
use YooKassa\Model\Receipt\OperationalDetails;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesExternal;
use YooKassa\Request\Payments\CreatePaymentRequestBuilder;
use YooKassa\Request\Payments\FraudData;
use YooKassa\Request\Payments\PaymentData\PaymentDataQiwi;
use YooKassa\Validator\Exceptions\ValidatorParameterException;

/**
 * @internal
 */
class CreatePaymentRequestBuilderTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetAccountId($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getRecipient());

        $builder->setAccountId($options['accountId']);
        $instance = $builder->build($this->getRequiredData('accountId'));

        if (null === $options['accountId'] || '' === $options['accountId']) {
            self::assertNull($instance->getRecipient());
        } else {
            self::assertNotNull($instance->getRecipient());
            self::assertEquals($options['accountId'], $instance->getRecipient()->getAccountId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetDeal($options): void
    {
        $builder = new CreatePaymentRequestBuilder();
        $builder->setAmount($options['amount']);
        $builder->setDeal($options['deal']);
        $instance = $builder->build();

        if (empty($options['deal'])) {
            self::assertNull($instance->getDeal());
        } else {
            self::assertNotNull($instance->getDeal());
            self::assertEquals($options['deal'], $instance->getDeal()->toArray());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetFraudData($options): void
    {
        $builder = new CreatePaymentRequestBuilder();
        $builder->setAmount($options['amount']);
        $builder->setFraudData($options['fraud_data']);
        $instance = $builder->build();

        if (empty($options['fraud_data'])) {
            self::assertNull($instance->getFraudData());
        } else {
            self::assertNotNull($instance->getFraudData());
            if (is_array($options['fraud_data'])) {
                self::assertEquals($options['fraud_data'], $instance->getFraudData()->toArray());
            } else {
                self::assertEquals($options['fraud_data'], $instance->getFraudData());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetMerchantCustomerId($options): void
    {
        $builder = new CreatePaymentRequestBuilder();
        $builder->setAmount($options['amount']);
        $builder->setMerchantCustomerId($options['merchant_customer_id']);
        $instance = $builder->build();

        if (empty($options['merchant_customer_id'])) {
            self::assertNull($instance->getMerchantCustomerId());
        } else {
            self::assertNotNull($instance->getMerchantCustomerId());
            self::assertEquals($options['merchant_customer_id'], $instance->getMerchantCustomerId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetProductGroupId($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getRecipient());

        $builder->setGatewayId($options['gatewayId']);
        $instance = $builder->build($this->getRequiredData('gatewayId'));

        if (empty($options['gatewayId'])) {
            self::assertNull($instance->getRecipient());
        } else {
            self::assertNotNull($instance->getRecipient());
            self::assertEquals($options['gatewayId'], $instance->getRecipient()->getGatewayId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetAmount($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNotNull($instance->getAmount());

        $builder->setAmount($options['amount']);
        $instance = $builder->build($this->getRequiredData('amount'));

        if ($options['amount'] instanceof AmountInterface) {
            self::assertEquals($options['amount']->getValue(), $instance->getAmount()->getValue());
            self::assertEquals($options['amount']->getCurrency(), $instance->getAmount()->getCurrency());
        } else {
            self::assertEquals($options['amount']['value'], $instance->getAmount()->getValue());
            self::assertEquals($options['amount']['currency'], $instance->getAmount()->getCurrency());
        }

        if (is_array($options['amount'])) {
            $builder->setAmount($options['amount']['value'], $options['amount']['currency']);
            $instance = $builder->build($this->getRequiredData('amount'));
            self::assertEquals($options['amount']['value'], $instance->getAmount()->getValue());
            self::assertEquals($options['amount']['currency'], $instance->getAmount()->getCurrency());
        }

        $builder->setAmount(10000)->setAmount($options['amount']);
        $instance = $builder->build($this->getRequiredData('amount'));

        if ($options['amount'] instanceof AmountInterface) {
            self::assertEquals($options['amount']->getValue(), $instance->getAmount()->getValue());
            self::assertEquals($options['amount']->getCurrency(), $instance->getAmount()->getCurrency());
        } else {
            self::assertEquals($options['amount']['value'], $instance->getAmount()->getValue());
            self::assertEquals($options['amount']['currency'], $instance->getAmount()->getCurrency());
        }

        if (!$options['amount'] instanceof AmountInterface) {
            $builder->setAmount([
                'value' => $options['amount']['value'],
                'currency' => $options['amount']['currency'],
            ]);
            $instance = $builder->build($this->getRequiredData('amount'));
            self::assertEquals($options['amount']['value'], $instance->getAmount()->getValue());
            self::assertEquals($options['amount']['currency'], $instance->getAmount()->getCurrency());
        }
    }

    /**
     * @dataProvider invalidAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAmount($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setAmount($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetCurrency($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNotNull($instance->getAmount());
        self::assertEquals(CurrencyCode::RUB, $instance->getAmount()->getCurrency());

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setAmount($options['amount']);
        $builder->setCurrency($options['amount']['currency']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $instance = $builder->build($this->getRequiredData('amount'));

        if ($options['amount'] instanceof AmountInterface) {
            self::assertEquals($options['amount']->getCurrency(), $instance->getAmount()->getCurrency());
        } else {
            self::assertEquals($options['amount']['currency'], $instance->getAmount()->getCurrency());
        }
        if (!empty($options['receiptItems'])) {
            foreach ($instance->getReceipt()->getItems() as $item) {
                self::assertEquals($options['amount']['currency'], $item->getPrice()->getCurrency());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetReceiptItems($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals(count($options['receiptItems']), count($instance->getReceipt()->getItems()));
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testAddReceiptItems($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        foreach ($options['receiptItems'] as $item) {
            if ($item instanceof ReceiptItem) {
                $builder->addReceiptItem(
                    $item->getDescription(),
                    $item->getPrice()->getValue(),
                    $item->getQuantity(),
                    $item->getVatCode(),
                    $item->getPaymentMode(),
                    $item->getPaymentSubject()
                );
            } else {
                $builder->addReceiptItem(
                    $item['description'],
                    $item['price']['value'],
                    $item['quantity'],
                    $item['vatCode'],
                    $item['paymentMode'],
                    $item['paymentSubject']
                );
            }
        }
        $builder->setReceiptEmail($options['receiptEmail']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals(count($options['receiptItems']), count($instance->getReceipt()->getItems()));
            foreach ($instance->getReceipt()->getItems() as $item) {
                self::assertFalse($item->isShipping());
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testAddReceiptShipping($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        foreach ($options['receiptItems'] as $item) {
            if ($item instanceof ReceiptItem) {
                $builder->addReceiptShipping(
                    $item->getDescription(),
                    $item->getPrice()->getValue(),
                    $item->getVatCode()
                );
            } else {
                $builder->addReceiptShipping($item['description'], $item['price']['value'], $item['vatCode']);
            }
        }
        $builder->setReceiptEmail($options['receiptEmail']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals(count($options['receiptItems']), count($instance->getReceipt()->getItems()));
            foreach ($instance->getReceipt()->getItems() as $item) {
                self::assertTrue($item->isShipping());
            }
        }
    }

    /**
     * @dataProvider invalidItemsDataProvider
     *
     * @param mixed $items
     */
    public function testSetInvalidReceiptItems($items): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setReceiptItems($items);
    }

    public static function invalidItemsDataProvider()
    {
        return [
            [
                [
                    [
                        'price' => [1],
                        'quantity' => -1.4,
                        'vatCode' => 10,
                    ],
                ],
            ],
            [
                [
                    [
                        'title' => 'test',
                        'quantity' => -1.4,
                        'vatCode' => 3,
                    ],
                ],
            ],
            [
                [
                    [
                        'description' => 'test',
                        'quantity' => 1.4,
                        'vatCode' => -3,
                    ],
                ],
            ],
            [
                [
                    [
                        'title' => 'test',
                        'price' => [123],
                        'quantity' => 1.4,
                        'vatCode' => 7,
                    ],
                ],
            ],
            [
                [
                    [
                        'description' => 'test',
                        'price' => [123],
                        'quantity' => -1.4,
                    ],
                ],
            ],
            [
                [
                    [
                        'title' => 'test',
                        'price' => [1],
                        'vatCode' => 7,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetReceiptEmail($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals($options['receiptEmail'], $instance->getReceipt()->getCustomer()->getEmail());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetReceiptPhone($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $builder->setReceiptPhone($options['receiptPhone']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals($options['receiptPhone'], $instance->getReceipt()->getCustomer()->getPhone());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetReceiptTaxSystemCode($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $builder->setTaxSystemCode($options['taxSystemCode']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals($options['taxSystemCode'], $instance->getReceipt()->getTaxSystemCode());
        }
    }

    /**
     * @dataProvider invalidVatIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidTaxSystemId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setTaxSystemCode($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetReceiptIndustryDetails($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $builder->setReceiptIndustryDetails($options['receiptIndustryDetails']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals($options['receiptIndustryDetails'], $instance->getReceipt()->getReceiptIndustryDetails()->toArray());
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
        $builder = new CreatePaymentRequestBuilder();
        $builder->setReceiptIndustryDetails($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetReceiptOperationalDetails($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setReceiptItems($options['receiptItems']);
        $builder->setReceiptEmail($options['receiptEmail']);
        $builder->setReceiptOperationalDetails($options['receiptOperationalDetails']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['receiptItems'])) {
            self::assertNull($instance->getReceipt());
        } else {
            self::assertNotNull($instance->getReceipt());
            self::assertEquals($options['receiptOperationalDetails'], $instance->getReceipt()->getReceiptOperationalDetails());
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
        $builder = new CreatePaymentRequestBuilder();
        $builder->setReceiptOperationalDetails($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetPaymentToken($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData(null, 'paymentMethodId'));
        self::assertNull($instance->getPaymentToken());
        self::assertNull($instance->paymentToken);
        self::assertNull($instance->payment_token);

        if (empty($options['paymentToken'])) {
            $buildData = $this->getRequiredData(null, 'paymentMethodId');
        } else {
            $buildData = $this->getRequiredData('paymentToken');
        }

        $builder->setPaymentToken($options['paymentToken']);
        $instance = $builder->build($buildData);

        if (empty($options['paymentToken'])) {
            self::assertNull($instance->getPaymentToken());
            self::assertNull($instance->paymentToken);
            self::assertNull($instance->payment_token);
        } else {
            self::assertEquals($options['paymentToken'], $instance->getPaymentToken());
            self::assertEquals($options['paymentToken'], $instance->paymentToken);
            self::assertEquals($options['paymentToken'], $instance->payment_token);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetPaymentMethodId($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getPaymentMethodId());

        $builder->setPaymentMethodId($options['paymentMethodId']);
        $instance = $builder->build($this->getRequiredData(empty($options['paymentMethodId']) ? null : 'paymentToken'));

        if (empty($options['paymentMethodId'])) {
            self::assertNull($instance->getPaymentMethodId());
        } else {
            self::assertEquals($options['paymentMethodId'], $instance->getPaymentMethodId());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetPaymentData($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getPaymentMethodData());

        $builder->setPaymentMethodData($options['paymentMethodData']);
        $instance = $builder->build($this->getRequiredData(empty($options['paymentMethodId']) ? null : 'paymentToken'));

        if (empty($options['paymentMethodData'])) {
            self::assertNull($instance->getPaymentMethodData());
        } else {
            if (is_object($options['paymentMethodData'])) {
                self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData());
            } elseif (is_string($options['paymentMethodData'])) {
                self::assertEquals($options['paymentMethodData'], $instance->getPaymentMethodData()->getType());
            } else {
                self::assertEquals($options['paymentMethodData']['type'], $instance->getPaymentMethodData()->getType());
            }
        }

        if (is_array($options['paymentMethodData'])) {
            $builder = new CreatePaymentRequestBuilder();
            $builder->build($this->getRequiredData());
            $builder->setPaymentMethodData($options['paymentMethodData']['type'], $options['paymentMethodData']);
            $instance = $builder->build($this->getRequiredData(empty($options['paymentMethodId']) ? null : 'paymentToken'));
            self::assertEquals($options['paymentMethodData']['type'], $instance->getPaymentMethodData()->getType());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetConfirmationAttributes($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getConfirmation());

        $builder->setConfirmation($options['confirmation']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['confirmation'])) {
            self::assertNull($instance->getConfirmation());
        } else {
            if (is_object($options['confirmation'])) {
                self::assertSame($options['confirmation'], $instance->getConfirmation());
            } elseif (is_string($options['confirmation'])) {
                self::assertEquals($options['confirmation'], $instance->getConfirmation()->getType());
            } else {
                self::assertEquals($options['confirmation']['type'], $instance->getConfirmation()->getType());
                self::assertEquals($options['confirmation']['locale'], $instance->getConfirmation()->getLocale());
            }
        }

        if (is_array($options['confirmation'])) {
            $builder = new CreatePaymentRequestBuilder();
            $builder->build($this->getRequiredData());
            $builder->setConfirmation($options['confirmation']);
            $instance = $builder->build($this->getRequiredData());
            self::assertEquals($options['confirmation']['type'], $instance->getConfirmation()->getType());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetCreateRecurring($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertFalse($instance->getSavePaymentMethod());

        $builder->setSavePaymentMethod($options['savePaymentMethod']);
        $instance = $builder->build($this->getRequiredData());

        if (null === $options['savePaymentMethod'] || '' === $options['savePaymentMethod']) {
            self::assertFalse($instance->getSavePaymentMethod());
        } else {
            self::assertEquals($options['savePaymentMethod'], $instance->getSavePaymentMethod());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetCapture($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertTrue($instance->getCapture());

        $builder->setCapture($options['capture']);
        $instance = $builder->build($this->getRequiredData());

        if (null === $options['capture'] || '' === $options['capture']) {
            self::assertTrue($instance->getCapture());
        } else {
            self::assertEquals($options['capture'], $instance->getCapture());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetClientIp($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getClientIp());

        $builder->setClientIp($options['clientIp']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['clientIp'])) {
            self::assertNull($instance->getClientIp());
        } else {
            self::assertEquals($options['clientIp'], $instance->getClientIp());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetMetadata($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $instance = $builder->build($this->getRequiredData());
        self::assertNull($instance->getMetadata());

        $builder->setMetadata($options['metadata']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['metadata'])) {
            self::assertNull($instance->getMetadata());
        } else {
            self::assertEquals($options['metadata'], $instance->getMetadata()->toArray());
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetRecipient($options): void
    {
        $recipient = new Recipient();
        $recipient->setAccountId($options['accountId']);
        $recipient->setGatewayId($options['gatewayId']);

        $builder = new CreatePaymentRequestBuilder();
        $builder->setRecipient($recipient);
        $instance = $builder->build($this->getRequiredData());

        self::assertEquals($recipient, $instance->getRecipient());

        $builder = new CreatePaymentRequestBuilder();
        $builder->setRecipient([
            'account_id' => $options['accountId'],
            'gateway_id' => $options['gatewayId'],
        ]);
        $instance = $builder->build($this->getRequiredData());

        self::assertEquals($recipient, $instance->getRecipient());
    }

    /**
     * @dataProvider invalidRecipientDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidRecipient($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setRecipient($value);
    }

    public static function invalidRecipientDataProvider()
    {
        return [
            [null],
            [true],
            [false],
            [1],
            [1.1],
            ['test'],
            [new stdClass()],
        ];
    }

    /**
     * @throws Exception
     */
    public function testSetReceipt(): void
    {
        $receipt = [
            'tax_system_code' => Random::int(1, 6),
            'customer' => [
                'email' => 'johndoe@yoomoney.ru',
                'phone' => Random::str(4, 15, '0123456789'),
            ],
            'items' => [
                [
                    'description' => 'test',
                    'quantity' => 123,
                    'amount' => [
                        'value' => 321,
                        'currency' => 'USD',
                    ],
                    'vat_code' => Random::int(1, 6),
                    'payment_subject' => PaymentSubject::COMMODITY,
                    'payment_mode' => PaymentMode::PARTIAL_PREPAYMENT,
                ],
            ],
        ];

        $builder = new CreatePaymentRequestBuilder();
        $builder->setReceipt($receipt);
        $instance = $builder->build($this->getRequiredData());

        self::assertEquals($receipt['tax_system_code'], $instance->getReceipt()->getTaxSystemCode());
        self::assertEquals($receipt['customer']['email'], $instance->getReceipt()->getCustomer()->getEmail());
        self::assertEquals($receipt['customer']['phone'], $instance->getReceipt()->getCustomer()->getPhone());
        self::assertCount(1, $instance->getReceipt()->getItems());

        $receipt = $instance->getReceipt();

        $builder = new CreatePaymentRequestBuilder();
        $builder->setReceipt($instance->getReceipt());
        $instance = $builder->build($this->getRequiredData());

        self::assertEquals($receipt['tax_system_code'], $instance->getReceipt()->getTaxSystemCode());
        self::assertEquals($receipt['customer']['email'], $instance->getReceipt()->getCustomer()->getEmail());
        self::assertEquals($receipt['customer']['phone'], $instance->getReceipt()->getCustomer()->getPhone());
        self::assertCount(1, $instance->getReceipt()->getItems());
    }

    /**
     * @dataProvider invalidReceiptDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidReceipt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setReceipt($value);
    }

    public static function invalidReceiptDataProvider()
    {
        return [
            [null],
            [true],
            [false],
            [1],
            [1.1],
            ['test'],
            [new stdClass()],
        ];
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $receiptItem = new ReceiptItem();
        $receiptItem->setPrice(new ReceiptItemAmount(1));
        $receiptItem->setQuantity(1);
        $receiptItem->setDescription('test');
        $receiptItem->setVatCode(3);
        $result = [
            [
                [
                    'accountId' => Random::str(1, 32),
                    'gatewayId' => Random::str(1, 32),
                    'recipient' => null,
                    'description' => Random::str(1, 128),
                    'amount' => new MonetaryAmount(Random::int(1, 1000)),
                    'receiptItems' => [],
                    'paymentToken' => null,
                    'paymentMethodId' => null,
                    'paymentMethodData' => null,
                    'confirmation' => null,
                    'savePaymentMethod' => true,
                    'capture' => true,
                    'clientIp' => null,
                    'metadata' => null,
                    'receiptEmail' => 'johndoe@yoomoney.ru',
                    'receiptPhone' => null,
                    'taxSystemCode' => null,
                    'deal' => [
                        'id' => Random::str(36, 50),
                        'settlements' => [
                            [
                                'type' => SettlementPayoutPaymentType::PAYOUT,
                                'amount' => [
                                    'value' => round(Random::float(10.00, 100.00), 2),
                                    'currency' => Random::value(CurrencyCode::getValidValues()),
                                ],
                            ],
                        ],
                    ],
                    'fraud_data' => null,
                    'merchant_customer_id' => null,
                    'receiptIndustryDetails' => [],
                    'receiptOperationalDetails' => null,
                ],
            ],
            [
                [
                    'accountId' => Random::str(1, 32),
                    'gatewayId' => Random::str(1, 32),
                    'recipient' => null,
                    'description' => Random::str(1, 128),
                    'amount' => new MonetaryAmount(Random::int(1, 1000)),
                    'receiptItems' => [
                        [
                            'description' => 'test',
                            'quantity' => Random::int(1, 100),
                            'price' => (new MonetaryAmount(Random::int(1, 1000)))->toArray(),
                            'vatCode' => Random::int(1, 6),
                            'paymentMode' => PaymentMode::CREDIT_PAYMENT,
                            'paymentSubject' => PaymentSubject::ANOTHER,
                        ],
                        $receiptItem,
                    ],
                    'referenceId' => null,
                    'paymentToken' => null,
                    'paymentMethodId' => null,
                    'paymentMethodData' => null,
                    'confirmation' => null,
                    'savePaymentMethod' => false,
                    'capture' => false,
                    'clientIp' => '',
                    'metadata' => [],
                    'receiptEmail' => 'johndoe@yoomoney.ru',
                    'receiptPhone' => '',
                    'taxSystemCode' => null,
                    'deal' => [
                        'id' => Random::str(36, 50),
                        'settlements' => [
                            [
                                'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                                'amount' => [
                                    'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                                    'currency' => Random::value(CurrencyCode::getValidValues()),
                                ],
                            ]
                        ],
                    ],
                    'fraud_data' => new FraudData([
                        'id' => Random::str(11, 15, '0123456789'),
                    ]),
                    'merchant_customer_id' => null,
                    'receiptIndustryDetails' => [],
                    'receiptOperationalDetails' => null,
                ],
            ],
        ];
        $paymentMethodData = [
            new PaymentDataQiwi(['phone' => Random::str(11, '0123456789')]),
            PaymentMethodType::BANK_CARD,
            [
                'type' => PaymentMethodType::BANK_CARD,
            ],
        ];
        $confirmationStatuses = [
            new ConfirmationAttributesExternal(),
            [
                'type' => ConfirmationType::EMBEDDED,
                'locale' => 'en_US',
            ],
            [
                'type' => ConfirmationType::EXTERNAL,
                'locale' => 'en_US',
            ],
            [
                'type' => ConfirmationType::QR,
                'locale' => 'ru_RU',
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'accountId' => uniqid('', true),
                'gatewayId' => uniqid('', true),
                'recipient' => new Recipient(),
                'description' => uniqid('', true),
                'amount' => [
                    'value' => Random::int(1, 100000),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'receiptItems' => [],
                'referenceId' => uniqid('', true),
                'paymentToken' => uniqid('', true),
                'paymentMethodId' => uniqid('', true),
                'paymentMethodData' => $paymentMethodData[$i] ?? null,
                'confirmation' => $confirmationStatuses[$i] ?? null,
                'savePaymentMethod' => (bool)Random::int(0, 1),
                'capture' => (bool)Random::int(0, 1),
                'clientIp' => long2ip(Random::int(0, 2 ** 32)),
                'metadata' => ['test' => 'test'],
                'receiptEmail' => 'johndoe@yoomoney.ru',
                'receiptPhone' => Random::str(10, '0123456789'),
                'taxSystemCode' => Random::int(1, 6),
                'receiptIndustryDetails' => [
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
                'receiptOperationalDetails' => [
                    'operation_id' => Random::int(0, OperationalDetails::OPERATION_ID_MAX_VALUE),
                    'value' => Random::str(1, OperationalDetails::VALUE_MAX_LENGTH),
                    'created_at' => date(YOOKASSA_DATE),
                ],
                'deal' => [
                    'id' => Random::str(36, 50),
                    'settlements' => [
                        [
                            'type' => SettlementPayoutPaymentType::PAYOUT,
                            'amount' => [
                                'value' => round(Random::float(10.00, 100.00), 2),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ],
                    ],
                ],
                'fraud_data' => [
                    'id' => Random::str(11, 15, '0123456789'),
                ],
                'merchant_customer_id' => Random::str(3, 100),
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidAmountDataProvider(): array
    {
        return [
            [-1],
            [true],
            [false],
            [new stdClass()],
            [0],
        ];
    }

    /**
     * @throws Exception
     */
    public static function invalidVatIdDataProvider(): array
    {
        return [
            [false],
            [0],
            [7],
            [Random::int(-100, -1)],
            [Random::int(7, 100)],
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

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     *
     * @throws Exception
     */
    public function testSetDescription($options): void
    {
        $builder = new CreatePaymentRequestBuilder();

        $builder->setDescription($options['description']);
        $instance = $builder->build($this->getRequiredData());

        if (empty($options['description'])) {
            self::assertNull($instance->getDescription());
        } else {
            self::assertEquals($options['description'], $instance->getDescription());
        }
    }

    public function testSetInvalidLengthDescription(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $description = Random::str(Payment::MAX_LENGTH_DESCRIPTION + 1);
        $builder->setDescription($description);
    }

    /**
     * @dataProvider invalidVatIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidAirline($value): void
    {
        $this->expectException(ValidatorParameterException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setAirline($value);
    }

    /**
     * @throws Exception
     */
    public static function invalidDealDataProvider(): array
    {
        return [
            [true],
            [false],
            [new stdClass()],
            [0],
            [7],
            [Random::int(-100, -1)],
            [Random::int(7, 100)],
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
        $builder = new CreatePaymentRequestBuilder();
        $builder->setDeal($value);
    }

    /**
     * @throws Exception
     */
    public static function invalidFraudDataProvider(): array
    {
        return [
            [true],
            [false],
            [new stdClass()],
            [0],
            [7],
            [Random::int(-100, -1)],
            [Random::int(7, 100)],
        ];
    }

    /**
     * @dataProvider invalidFraudDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidFraudData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $builder = new CreatePaymentRequestBuilder();
        $builder->setFraudData($value);
    }

    /**
     * @param null $testingProperty
     * @param null $paymentType
     *
     * @throws Exception
     */
    protected function getRequiredData($testingProperty = null, $paymentType = null): array
    {
        $result = [];
        if ('accountId' === $testingProperty || 'gatewayId' === $testingProperty) {
            if ('accountId' !== $testingProperty) {
                $result['accountId'] = Random::str(1, 32);
            }
            if ('gatewayId' !== $testingProperty) {
                $result['gatewayId'] = Random::str(1, 32);
            }
        }
        if ('amount' !== $testingProperty) {
            $result['amount'] = new MonetaryAmount(Random::int(1, 100));
        }
        if ('paymentToken' !== $testingProperty) {
            if (null !== $paymentType) {
                $result[$paymentType] = Random::str(36);
            } else {
                $result['paymentToken'] = Random::str(36);
            }
        }

        return $result;
    }
}
