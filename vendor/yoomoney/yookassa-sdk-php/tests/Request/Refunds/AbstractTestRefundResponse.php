<?php

namespace Tests\YooKassa\Request\Refunds;

use DateTime;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\AmountInterface;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Deal\RefundDealInfo;
use YooKassa\Model\Deal\SettlementPayoutPaymentType;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\ReceiptRegistrationStatus;
use YooKassa\Model\Refund\RefundStatus;
use YooKassa\Model\Refund\Source;
use YooKassa\Request\Refunds\AbstractRefundResponse;

abstract class AbstractTestRefundResponse extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetId(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetPaymentId(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['payment_id'], $instance->getPaymentId());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetStatus(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['status'], $instance->getStatus());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetCreatedAt(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertInstanceOf(DateTime::class, $instance->getCreatedAt());
        self::assertEquals($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetAmount(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertInstanceOf(AmountInterface::class, $instance->getAmount());
        self::assertEquals($options['amount']['value'], $instance->getAmount()->getValue());
        self::assertEquals($options['amount']['currency'], $instance->getAmount()->getCurrency());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetReceiptRegistered(array $options): void
    {
        $instance = $this->getTestInstance($options);
        if (empty($options['receipt_registration'])) {
            self::assertNull($instance->getReceiptRegistration());
        } else {
            self::assertEquals($options['receipt_registration'], $instance->getReceiptRegistration());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetDescription(array $options): void
    {
        $instance = $this->getTestInstance($options);
        if (empty($options['description'])) {
            self::assertNull($instance->getDescription());
        } else {
            self::assertEquals($options['description'], $instance->getDescription());
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSources(array $options): void
    {
        $instance = $this->getTestInstance($options);
        if (empty($options['sources'])) {
            self::assertEmpty($instance->getSources());
        } else {
            foreach ($instance->getSources() as $sources) {
                self::assertInstanceOf(Source::class, $sources);
            }
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetDeal(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertInstanceOf(RefundDealInfo::class, $instance->getDeal());

        self::assertEquals($options['deal']['id'], $instance->getDeal()->getId());
        $settlements = $instance->getDeal()->getRefundSettlements();
        if (!empty($settlements)) {
            self::assertEquals($options['deal']['refund_settlements'][0], $settlements[0]->toArray());
        }
    }

    public static function validDataProvider()
    {
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $payment = [
                'id' => Random::str(36),
                'payment_id' => Random::str(36),
                'status' => Random::value(RefundStatus::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'authorized_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'amount' => [
                    'value' => Random::int(100, 100000),
                    'currency' => Random::value(CurrencyCode::getValidValues()),
                ],
                'receipt_registration' => Random::value(ReceiptRegistrationStatus::getValidValues()),
                'description' => uniqid('', true),
                'sources' => [
                    new Source([
                        'account_id' => Random::str(36),
                        'amount' => new MonetaryAmount(Random::int(1, 1000), 'RUB'),
                        'platform_fee_amount' => new MonetaryAmount(Random::int(1, 1000), 'RUB'),
                    ]),
                ],
                'deal' => [
                    'id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147',
                    'refund_settlements' => [
                        [
                            'type' => SettlementPayoutPaymentType::PAYOUT,
                            'amount' => [
                                'value' => 123.00,
                                'currency' => 'RUB',
                            ],
                        ],
                    ],
                ],
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    abstract protected function getTestInstance(array $options): AbstractRefundResponse;
}
