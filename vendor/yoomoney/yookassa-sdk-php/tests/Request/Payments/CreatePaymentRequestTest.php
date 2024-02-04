<?php

namespace Tests\YooKassa\Request\Payments;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\PaymentDealInfo;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\Metadata;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Model\Payment\Payment;
use YooKassa\Model\Payment\Recipient;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\Receipt;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesExternal;
use YooKassa\Request\Payments\CreatePaymentRequest;
use YooKassa\Request\Payments\CreatePaymentRequestBuilder;
use YooKassa\Request\Payments\FraudData;
use YooKassa\Request\Payments\Locale;
use YooKassa\Request\Payments\PaymentData\PaymentDataQiwi;
use YooKassa\Request\Payments\TransferData;

/**
 * @internal
 */
class CreatePaymentRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testRecipient($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasRecipient());
        self::assertNull($instance->getRecipient());
        self::assertNull($instance->recipient);

        $instance->setRecipient($options['recipient']);
        if (empty($options['recipient'])) {
            self::assertFalse($instance->hasRecipient());
            self::assertNull($instance->getRecipient());
            self::assertNull($instance->recipient);
        } else {
            self::assertTrue($instance->hasRecipient());
            if (is_array($options['recipient'])) {
                self::assertSame($options['recipient'], $instance->getRecipient()->toArray());
                self::assertSame($options['recipient'], $instance->recipient->toArray());
            } else {
                self::assertSame($options['recipient'], $instance->getRecipient());
                self::assertSame($options['recipient'], $instance->recipient);
            }
        }

        $instance->setRecipient(null);
        self::assertFalse($instance->hasRecipient());
        self::assertNull($instance->getRecipient());
        self::assertNull($instance->recipient);

        $instance->recipient = $options['recipient'];
        if (empty($options['recipient'])) {
            self::assertFalse($instance->hasRecipient());
            self::assertNull($instance->getRecipient());
            self::assertNull($instance->recipient);
        } else {
            self::assertTrue($instance->hasRecipient());
            if (is_array($options['recipient'])) {
                self::assertSame($options['recipient'], $instance->getRecipient()->toArray());
                self::assertSame($options['recipient'], $instance->recipient->toArray());
            } else {
                self::assertSame($options['recipient'], $instance->getRecipient());
                self::assertSame($options['recipient'], $instance->recipient);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testAmount($options): void
    {
        $instance = new CreatePaymentRequest();

        $instance->setAmount($options['amount']);

        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPaymentToken($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasPaymentToken());
        self::assertNull($instance->getPaymentToken());
        self::assertNull($instance->paymentToken);

        $instance->setPaymentToken($options['paymentToken']);
        if (empty($options['paymentToken'])) {
            self::assertFalse($instance->hasPaymentToken());
            self::assertNull($instance->getPaymentToken());
            self::assertNull($instance->paymentToken);
            self::assertNull($instance->payment_token);
        } else {
            self::assertTrue($instance->hasPaymentToken());
            self::assertSame($options['paymentToken'], $instance->getPaymentToken());
            self::assertSame($options['paymentToken'], $instance->paymentToken);
            self::assertSame($options['paymentToken'], $instance->payment_token);
        }

        $instance->setPaymentToken(null);
        self::assertFalse($instance->hasPaymentToken());
        self::assertNull($instance->getPaymentToken());
        self::assertNull($instance->paymentToken);

        $instance->paymentToken = $options['paymentToken'];
        if (empty($options['paymentToken'])) {
            self::assertFalse($instance->hasPaymentToken());
            self::assertNull($instance->getPaymentToken());
            self::assertNull($instance->paymentToken);
            self::assertNull($instance->payment_token);
        } else {
            self::assertTrue($instance->hasPaymentToken());
            self::assertSame($options['paymentToken'], $instance->getPaymentToken());
            self::assertSame($options['paymentToken'], $instance->paymentToken);
            self::assertSame($options['paymentToken'], $instance->payment_token);
        }

        $instance->paymentToken = null;
        self::assertFalse($instance->hasPaymentToken());
        self::assertNull($instance->getPaymentToken());
        self::assertNull($instance->paymentToken);

        $instance->payment_token = $options['paymentToken'];
        if (empty($options['paymentToken'])) {
            self::assertFalse($instance->hasPaymentToken());
            self::assertNull($instance->getPaymentToken());
            self::assertNull($instance->paymentToken);
            self::assertNull($instance->payment_token);
        } else {
            self::assertTrue($instance->hasPaymentToken());
            self::assertSame($options['paymentToken'], $instance->getPaymentToken());
            self::assertSame($options['paymentToken'], $instance->paymentToken);
            self::assertSame($options['paymentToken'], $instance->payment_token);
        }
    }

    /**
     * @dataProvider invalidPaymentTokenDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentToken($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->setPaymentToken($value);
    }

    /**
     * @dataProvider invalidPaymentTokenDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPaymentToken($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->paymentToken = $value;
    }

    /**
     * @dataProvider invalidPaymentTokenDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakePaymentToken($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->payment_token = $value;
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPaymentMethodId($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);
        self::assertNull($instance->payment_method_id);

        $instance->setPaymentMethodId($options['paymentMethodId']);
        if (empty($options['paymentMethodId'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
            self::assertNull($instance->payment_method_id);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->getPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->paymentMethodId);
            self::assertSame($options['paymentMethodId'], $instance->payment_method_id);
        }

        $instance->setPaymentMethodId(null);
        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);

        $instance->paymentMethodId = $options['paymentMethodId'];
        if (empty($options['paymentMethodId'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
            self::assertNull($instance->payment_method_id);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->getPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->paymentMethodId);
            self::assertSame($options['paymentMethodId'], $instance->payment_method_id);
        }

        $instance->setPaymentMethodId(null);
        self::assertFalse($instance->hasPaymentMethodId());
        self::assertNull($instance->getPaymentMethodId());
        self::assertNull($instance->paymentMethodId);

        $instance->payment_method_id = $options['paymentMethodId'];
        if (empty($options['paymentMethodId'])) {
            self::assertFalse($instance->hasPaymentMethodId());
            self::assertNull($instance->getPaymentMethodId());
            self::assertNull($instance->paymentMethodId);
            self::assertNull($instance->payment_method_id);
        } else {
            self::assertTrue($instance->hasPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->getPaymentMethodId());
            self::assertSame($options['paymentMethodId'], $instance->paymentMethodId);
            self::assertSame($options['paymentMethodId'], $instance->payment_method_id);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPaymentData($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasPaymentMethodData());
        self::assertNull($instance->getPaymentMethodData());
        self::assertNull($instance->paymentMethodData);

        $instance->setPaymentMethodData($options['paymentMethodData']);
        if (empty($options['paymentMethodData'])) {
            self::assertFalse($instance->hasPaymentMethodData());
            self::assertNull($instance->getPaymentMethodData());
            self::assertNull($instance->paymentMethodData);
        } else {
            self::assertTrue($instance->hasPaymentMethodData());
            if (is_array($options['paymentMethodData'])) {
                self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData()->toArray());
                self::assertSame($options['paymentMethodData'], $instance->paymentMethodData->toArray());
            } else {
                self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData());
                self::assertSame($options['paymentMethodData'], $instance->paymentMethodData);
            }
        }

        $instance->setPaymentMethodData(null);
        self::assertFalse($instance->hasPaymentMethodData());
        self::assertNull($instance->getPaymentMethodData());
        self::assertNull($instance->paymentMethodData);

        $instance->paymentMethodData = $options['paymentMethodData'];
        if (empty($options['paymentMethodData'])) {
            self::assertFalse($instance->hasPaymentMethodData());
            self::assertNull($instance->getPaymentMethodData());
            self::assertNull($instance->paymentMethodData);
        } else {
            if (is_array($options['paymentMethodData'])) {
                self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData()->toArray());
                self::assertSame($options['paymentMethodData'], $instance->paymentMethodData->toArray());
            } else {
                self::assertSame($options['paymentMethodData'], $instance->getPaymentMethodData());
                self::assertSame($options['paymentMethodData'], $instance->paymentMethodData);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testConfirmationAttributes($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->setConfirmation($options['confirmation']);
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            if (is_array($options['confirmation'])) {
                self::assertSame($options['confirmation'], $instance->getConfirmation()->toArray());
                self::assertSame($options['confirmation'], $instance->confirmation->toArray());
            } else {
                self::assertSame($options['confirmation'], $instance->getConfirmation());
                self::assertSame($options['confirmation'], $instance->confirmation);
            }
        }

        $instance->setConfirmation(null);
        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $instance->confirmation = $options['confirmation'];
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            if (is_array($options['confirmation'])) {
                self::assertSame($options['confirmation'], $instance->getConfirmation()->toArray());
                self::assertSame($options['confirmation'], $instance->confirmation->toArray());
            } else {
                self::assertSame($options['confirmation'], $instance->getConfirmation());
                self::assertSame($options['confirmation'], $instance->confirmation);
            }
        }
    }

    /**
     * @dataProvider invalidConfirmationAttributesDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidConfirmationAttributes($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->setConfirmation($value);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCreateRecurring($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertTrue($instance->hasSavePaymentMethod());
        self::assertFalse($instance->getSavePaymentMethod());
        self::assertFalse($instance->savePaymentMethod);

        $instance->setSavePaymentMethod($options['savePaymentMethod']);
        if (null === $options['savePaymentMethod'] || '' === $options['savePaymentMethod']) {
            self::assertTrue($instance->hasSavePaymentMethod());
            self::assertFalse($instance->getSavePaymentMethod());
            self::assertFalse($instance->savePaymentMethod);
        } else {
            self::assertTrue($instance->hasSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->getSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->savePaymentMethod);
        }

        $instance->savePaymentMethod = $options['savePaymentMethod'];
        if (null === $options['savePaymentMethod'] || '' === $options['savePaymentMethod']) {
            self::assertTrue($instance->hasSavePaymentMethod());
            self::assertFalse($instance->getSavePaymentMethod());
            self::assertFalse($instance->savePaymentMethod);
        } else {
            self::assertTrue($instance->hasSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->getSavePaymentMethod());
            self::assertSame($options['savePaymentMethod'], $instance->savePaymentMethod);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testCapture($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertTrue($instance->hasCapture());
        self::assertTrue($instance->getCapture());
        self::assertTrue($instance->capture);

        $instance->setCapture($options['capture']);
        if (null === $options['capture'] || '' === $options['capture']) {
            self::assertTrue($instance->hasCapture());
            self::assertTrue($instance->getCapture());
            self::assertTrue($instance->capture);
        } else {
            self::assertTrue($instance->hasCapture());
            self::assertSame($options['capture'], $instance->getCapture());
            self::assertSame($options['capture'], $instance->capture);
        }

        $instance->capture = $options['capture'];
        if (null === $options['capture'] || '' === $options['capture']) {
            self::assertTrue($instance->hasCapture());
            self::assertTrue($instance->getCapture());
            self::assertTrue($instance->capture);
        } else {
            self::assertTrue($instance->hasCapture());
            self::assertSame($options['capture'], $instance->getCapture());
            self::assertSame($options['capture'], $instance->capture);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testClientIp($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasClientIp());
        self::assertNull($instance->getClientIp());
        self::assertNull($instance->clientIp);

        $instance->setClientIp($options['clientIp']);
        if (empty($options['clientIp'])) {
            self::assertFalse($instance->hasClientIp());
            self::assertNull($instance->getClientIp());
            self::assertNull($instance->clientIp);
        } else {
            self::assertTrue($instance->hasClientIp());
            self::assertSame($options['clientIp'], $instance->getClientIp());
            self::assertSame($options['clientIp'], $instance->clientIp);
        }

        $instance->setClientIp(null);
        self::assertFalse($instance->hasClientIp());
        self::assertNull($instance->getClientIp());
        self::assertNull($instance->clientIp);

        $instance->clientIp = $options['clientIp'];
        if (empty($options['clientIp'])) {
            self::assertFalse($instance->hasClientIp());
            self::assertNull($instance->getClientIp());
            self::assertNull($instance->clientIp);
        } else {
            self::assertTrue($instance->hasClientIp());
            self::assertSame($options['clientIp'], $instance->getClientIp());
            self::assertSame($options['clientIp'], $instance->clientIp);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testMerchantCustomerId($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasMerchantCustomerId());
        self::assertNull($instance->getMerchantCustomerId());
        self::assertNull($instance->merchantCustomerId);
        self::assertNull($instance->merchant_customer_id);

        $instance->setMerchantCustomerId($options['merchant_customer_id']);
        if (empty($options['merchant_customer_id'])) {
            self::assertFalse($instance->hasMerchantCustomerId());
            self::assertNull($instance->getMerchantCustomerId());
            self::assertNull($instance->merchantCustomerId);
            self::assertNull($instance->merchant_customer_id);
        } else {
            self::assertTrue($instance->hasMerchantCustomerId());
            self::assertSame($options['merchant_customer_id'], $instance->getMerchantCustomerId());
            self::assertSame($options['merchant_customer_id'], $instance->merchantCustomerId);
            self::assertSame($options['merchant_customer_id'], $instance->merchant_customer_id);
        }

        $instance->setMerchantCustomerId(null);
        self::assertFalse($instance->hasMerchantCustomerId());
        self::assertNull($instance->getMerchantCustomerId());
        self::assertNull($instance->merchantCustomerId);
        self::assertNull($instance->merchant_customer_id);

        $instance->merchant_customer_id = $options['merchant_customer_id'];
        if (empty($options['merchant_customer_id'])) {
            self::assertFalse($instance->hasMerchantCustomerId());
            self::assertNull($instance->getMerchantCustomerId());
            self::assertNull($instance->merchantCustomerId);
            self::assertNull($instance->merchant_customer_id);
        } else {
            self::assertTrue($instance->hasMerchantCustomerId());
            self::assertSame($options['merchant_customer_id'], $instance->getMerchantCustomerId());
            self::assertSame($options['merchant_customer_id'], $instance->merchantCustomerId);
            self::assertSame($options['merchant_customer_id'], $instance->merchant_customer_id);
        }
    }

    /**
     * @dataProvider invalidMerchantCustomerIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMerchantCustomerId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->setMerchantCustomerId($value);
    }

    public static function invalidMerchantCustomerIdDataProvider()
    {
        return [
            [Random::str(Payment::MAX_LENGTH_MERCHANT_CUSTOMER_ID + 1)],
        ];
    }

    /**
     * @dataProvider validTransfers
     *
     * @param mixed $value
     */
    public function testSetTransfer($value): void
    {
        $instance = new CreatePaymentRequest();
        $instance->setTransfers($value);
        if (is_array($value[0])) {
            $expected = [new TransferData($value[0])];
            self::assertEquals($expected[0]->toArray(), $instance->getTransfers()[0]->toArray());
        } else {
            self::assertEquals($value[0], $instance->getTransfers()[0]);
        }
    }

    /**
     * @return array[]
     *
     * @throws Exception
     */
    public static function validTransfers(): array
    {
        $transfers = [];
        for ($i = 0; $i < 10; $i++) {
            foreach (range(0, Random::int(1, 3)) as $item) {
                $transfers[$i][$item] = [
                    'account_id' => (string)Random::int(11111111, 99999999),
                    'amount' => [
                        'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                    'platform_fee_amount' => [
                        'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                        'currency' => Random::value(CurrencyCode::getValidValues()),
                    ],
                    'description' => Random::str(1, TransferData::MAX_LENGTH_DESCRIPTION),
                    'metadata' => ['test' => 'test'],
                ];
            }
        }
        $transfers[0][0] = Random::bool() ? $transfers[0][0] : new TransferData($transfers[0][0]);

        return [$transfers];
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testMetadata($options): void
    {
        $instance = new CreatePaymentRequest();

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
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testDeal($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $expected = $options['deal'];
        if ($expected instanceof PaymentDealInfo) {
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
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testFraudData($options): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->hasFraudData());
        self::assertNull($instance->getFraudData());
        self::assertNull($instance->fraud_data);

        $expected = $options['fraud_data'];
        if ($expected instanceof FraudData) {
            $expected = $expected->toArray();
        }

        $instance->setFraudData($options['fraud_data']);
        if (empty($options['fraud_data'])) {
            self::assertFalse($instance->hasFraudData());
            self::assertNull($instance->getFraudData());
            self::assertNull($instance->fraud_data);
        } else {
            self::assertTrue($instance->hasFraudData());
            self::assertSame($expected, $instance->getFraudData()->toArray());
            self::assertSame($expected, $instance->fraud_data->toArray());
        }

        $instance->setFraudData(null);
        self::assertFalse($instance->hasFraudData());
        self::assertNull($instance->getFraudData());
        self::assertNull($instance->fraud_data);

        $instance->fraud_data = $options['fraud_data'];
        if (empty($options['fraud_data'])) {
            self::assertFalse($instance->hasFraudData());
            self::assertNull($instance->getFraudData());
            self::assertNull($instance->fraud_data);
        } else {
            self::assertTrue($instance->hasFraudData());
            self::assertSame($expected, $instance->getFraudData()->toArray());
            self::assertSame($expected, $instance->fraud_data->toArray());
        }
    }

    public function testValidate(): void
    {
        $instance = new CreatePaymentRequest();

        self::assertFalse($instance->validate());

        $amount = new MonetaryAmount();
        $instance->setAmount($amount);
        self::assertFalse($instance->validate());

        $instance->setAmount(new MonetaryAmount(10));
        self::assertTrue($instance->validate());

        $instance->setPaymentToken(Random::str(10));
        self::assertTrue($instance->validate());
        $instance->setPaymentMethodId(Random::str(10));
        self::assertFalse($instance->validate());
        $instance->setPaymentMethodId(null);
        self::assertTrue($instance->validate());
        $instance->setPaymentMethodData(new PaymentDataQiwi(['phone' => Random::str(11, '0123456789')]));
        self::assertFalse($instance->validate());
        $instance->setPaymentToken(null);
        self::assertTrue($instance->validate());
        $instance->setPaymentMethodId(Random::str(10));
        self::assertFalse($instance->validate());
        $instance->setPaymentMethodId(null);
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

        self::assertNotNull($instance->getReceipt());
        $instance->removeReceipt();
        self::assertTrue($instance->validate());
        self::assertNull($instance->getReceipt());

        $instance->setAmount(new MonetaryAmount());
        self::assertFalse($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = CreatePaymentRequest::builder();
        self::assertInstanceOf(CreatePaymentRequestBuilder::class, $builder);
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidMetadata($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->setMetadata($value);
    }

    /**
     * @dataProvider invalidMetadataDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDeal($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->setDeal($value);
    }

    /**
     * @dataProvider invalidFraudDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidFraudData($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreatePaymentRequest();
        $instance->setFraudData($value);
    }

    public static function validDataProvider()
    {
        $metadata = new Metadata();
        $metadata->test = 'test';
        $result = [
            [
                [
                    'recipient' => null,
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'referenceId' => null,
                    'paymentToken' => null,
                    'paymentMethodId' => null,
                    'paymentMethodData' => null,
                    'confirmation' => null,
                    'savePaymentMethod' => false,
                    'capture' => true,
                    'clientIp' => null,
                    'metadata' => null,
                    'deal' => null,
                    'fraud_data' => null,
                    'merchant_customer_id' => null,
                ],
            ],
            [
                [
                    'recipient' => null,
                    'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                    'referenceId' => '',
                    'paymentToken' => '',
                    'paymentMethodId' => '',
                    'paymentMethodData' => null,
                    'confirmation' => null,
                    'savePaymentMethod' => true,
                    'capture' => true,
                    'clientIp' => '',
                    'metadata' => [],
                    'deal' => new PaymentDealInfo([
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
                    ]),
                    'fraud_data' => new FraudData([
                        'topped_up_phone' => Random::str(11, 15, '0123456789'),
                    ]),
                    'merchant_customer_id' => '',
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'recipient' => new Recipient(['account_id' => Random::str(11, '0123456789')]),
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                'referenceId' => uniqid('', true),
                'paymentToken' => uniqid('', true),
                'paymentMethodId' => uniqid('', true),
                'paymentMethodData' => new PaymentDataQiwi(['phone' => Random::str(11, '0123456789')]),
                'confirmation' => new ConfirmationAttributesExternal(),
                'savePaymentMethod' => Random::bool(),
                'capture' => Random::bool(),
                'clientIp' => long2ip(Random::int(0, 2 ** 32)),
                'metadata' => 0 === $i ? $metadata : ['test' => 'test'],
                'transfers' => [
                    [
                        'account_id' => (string) Random::int(11111111, 99999999),
                        'amount' => [
                            'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                            'currency' => Random::value(CurrencyCode::getValidValues()),
                        ],
                        'platform_fee_amount' => [
                            'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                            'currency' => Random::value(CurrencyCode::getValidValues()),
                        ],
                        'description' => Random::str(1, TransferData::MAX_LENGTH_DESCRIPTION),
                        'metadata' => 0 === $i ? $metadata : ['test' => 'test'],
                    ],
                ],
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
                'fraud_data' => [
                    'topped_up_phone' => Random::str(11, 15, '0123456789'),
                ],
                'merchant_customer_id' => Random::str(36, 50),
            ];
            $result[] = [$request];
        }

        $result[] = [
            [
                'recipient' => ['account_id' => Random::str(11, '0123456789')],
                'amount' => new MonetaryAmount(Random::int(1, 1000000)),
                'referenceId' => uniqid('', true),
                'paymentToken' => uniqid('', true),
                'paymentMethodId' => uniqid('', true),
                'paymentMethodData' => ['phone' => Random::str(11, '0123456789'), 'type' => 'qiwi', ],
                'confirmation' => [
                    'return_url' => 'https://test.com',
                    'type' => ConfirmationType::MOBILE_APPLICATION,
                    'locale' => Locale::RUSSIAN,
                ],
                'savePaymentMethod' => Random::bool(),
                'capture' => Random::bool(),
                'clientIp' => long2ip(Random::int(0, 2 ** 32)),
                'metadata' => Random::bool() ? $metadata : ['test' => 'test'],
                'transfers' => [
                    [
                        'account_id' => (string) Random::int(11111111, 99999999),
                        'amount' => [
                            'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                            'currency' => Random::value(CurrencyCode::getValidValues()),
                        ],
                        'platform_fee_amount' => [
                            'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                            'currency' => Random::value(CurrencyCode::getValidValues()),
                        ],
                        'description' => Random::str(1, TransferData::MAX_LENGTH_DESCRIPTION),
                        'metadata' => Random::bool() ? $metadata : ['test' => 'test'],
                    ],
                ],
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
                    'topped_up_phone' => Random::str(11, 15, '0123456789'),
                ]),
                'merchant_customer_id' => Random::str(36, 50),
            ],
        ];

        return $result;
    }

    public function invalidReferenceIdDataProvider()
    {
        return [
            [false],
            [true],
            [new stdClass()],
            [[]],
            [Random::str(32)],
        ];
    }

    public static function invalidPaymentTokenDataProvider()
    {
        return [
            [Random::str(CreatePaymentRequest::MAX_LENGTH_PAYMENT_TOKEN + 1)],
        ];
    }

    public static function invalidConfirmationAttributesDataProvider()
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

    public static function invalidMetadataDataProvider()
    {
        return [
            [false],
            [true],
            [1],
            [Random::str(10)],
        ];
    }

    public static function invalidFraudDataProvider()
    {
        return [
            [false],
            [true],
            [new stdClass()],
            [Random::str(16, 30, '0123456789')],
        ];
    }

}
