<?php

namespace Tests\YooKassa\Model\Payout;

use InvalidArgumentException;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payout\PayoutDestinationSbp;

/**
 * @internal
 */
class PayoutDestinationSbpTest extends AbstractTestPayoutDestination
{
    /**
     * @dataProvider validPhoneDataProvider
     */
    public function testGetSetPhone(string $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setPhone($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getPhone());
            self::assertEquals($expected, $instance->phone);
        }

        $instance = $this->getTestInstance();
        $instance->phone = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getPhone());
            self::assertEquals($expected, $instance->phone);
        }
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPhone($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setPhone($value);
    }

    /**
     * @dataProvider invalidPhoneDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidPhone($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->phone = $value;
    }

    /**
     * @dataProvider validBankIdDataProvider
     */
    public function testGetSetBankId(string $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setBankId($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getBankId());
            self::assertNull($instance->bankId);
            self::assertNull($instance->bank_id);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getBankId());
            self::assertEquals($expected, $instance->bankId);
            self::assertEquals($expected, $instance->bank_id);
        }

        $instance = $this->getTestInstance();
        $instance->bankId = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getBankId());
            self::assertNull($instance->bankId);
            self::assertNull($instance->bank_id);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getBankId());
            self::assertEquals($expected, $instance->bankId);
            self::assertEquals($expected, $instance->bank_id);
        }
    }

    /**
     * @dataProvider invalidBankIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidBankId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setBankId($value);
    }

    /**
     * @dataProvider invalidBankIdDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidBankId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->bankId = $value;
    }

    /**
     * @dataProvider validRecipientCheckedDataProvider
     */
    public function testGetSetRecipientChecked(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setRecipientChecked($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getRecipientChecked());
            self::assertNull($instance->recipientChecked);
            self::assertNull($instance->recipient_checked);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getRecipientChecked());
            self::assertEquals($expected, $instance->recipientChecked);
            self::assertEquals($expected, $instance->recipient_checked);
        }

        $instance = $this->getTestInstance();
        $instance->recipientChecked = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getRecipientChecked());
            self::assertNull($instance->recipientChecked);
            self::assertNull($instance->recipient_checked);
        } else {
            $expected = $value;
            self::assertEquals($expected, $instance->getRecipientChecked());
            self::assertEquals($expected, $instance->recipientChecked);
            self::assertEquals($expected, $instance->recipient_checked);
        }
    }

    public static function validPhoneDataProvider(): array
    {
        return [
            [Random::str(4, 15, '0123456789')],
            [Random::str(4, 15, '0123456789')],
        ];
    }

    public static function invalidPhoneDataProvider(): array
    {
        return [
            [null],
            [''],
        ];
    }

    public static function validBankIdDataProvider(): array
    {
        return [
            [Random::str(7, 12, '0123456789')],
            [Random::str(7, 12, '0123456789')],
        ];
    }

    public static function invalidBankIdDataProvider(): array
    {
        return [
            [null],
            [''],
            [Random::str(13)],
        ];
    }

    public static function validRecipientCheckedDataProvider(): array
    {
        return [
            [Random::bool()],
            [Random::bool()],
        ];
    }

    protected function getTestInstance(): PayoutDestinationSbp
    {
        return new PayoutDestinationSbp();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBP;
    }
}
