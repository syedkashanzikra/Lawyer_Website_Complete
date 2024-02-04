<?php

namespace Tests\YooKassa\Request\Payments;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Receipt\PaymentMode;
use YooKassa\Model\Receipt\PaymentSubject;
use YooKassa\Model\Receipt\Receipt;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;
use YooKassa\Request\Payments\CreateCaptureRequest;
use YooKassa\Request\Payments\CreateCaptureRequestBuilder;

/**
 * @internal
 */
class CreateCaptureRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testRecipient($options): void
    {
        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasAmount());
        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->setAmount($options['amount']);
        self::assertTrue($instance->hasAmount());
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);

        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasAmount());
        self::assertNull($instance->getAmount());
        self::assertNull($instance->amount);

        $instance->amount = $options['amount'];
        self::assertTrue($instance->hasAmount());
        self::assertSame($options['amount'], $instance->getAmount());
        self::assertSame($options['amount'], $instance->amount);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testDeal($options): void
    {
        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $instance->setDeal($options['deal']);
        if ($instance->hasDeal()) {
            self::assertTrue($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal()->toArray());
            self::assertSame($options['deal'], $instance->deal->toArray());
        } else {
            self::assertFalse($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal());
            self::assertSame($options['deal'], $instance->deal);
        }

        $instance = new CreateCaptureRequest();
        self::assertFalse($instance->hasDeal());
        self::assertNull($instance->getDeal());
        self::assertNull($instance->deal);

        $instance->deal = $options['deal'];
        if ($instance->hasDeal()) {
            self::assertTrue($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal()->toArray());
            self::assertSame($options['deal'], $instance->deal->toArray());
        } else {
            self::assertFalse($instance->hasDeal());
            self::assertSame($options['deal'], $instance->getDeal());
            self::assertSame($options['deal'], $instance->deal);
        }
    }

    public function testValidate(): void
    {
        $instance = new CreateCaptureRequest();

        self::assertTrue($instance->validate());
        $amount = new MonetaryAmount();
        $instance->setAmount($amount);
        self::assertFalse($instance->validate());
        $amount->setValue(1);
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
        $builder = CreateCaptureRequest::builder();
        self::assertInstanceOf(CreateCaptureRequestBuilder::class, $builder);
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $currencies = CurrencyCode::getValidValues();
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'amount' => new MonetaryAmount(Random::int(1, 1000000), $currencies[Random::int(0, count($currencies) - 1)]),
                'deal' => $i % 2 ? [
                    'settlements' => [
                        [
                            'type' => Random::value(SettlementPayoutPaymentType::getValidValues()),
                            'amount' => [
                                'value' => sprintf('%.2f', round(Random::float(0.1, 99.99), 2)),
                                'currency' => Random::value(CurrencyCode::getValidValues()),
                            ],
                        ]
                    ],
                ] : null,
            ];
            $result[] = [$request];
        }

        return $result;
    }
}
