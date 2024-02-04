<?php

namespace Tests\YooKassa\Model\Payment\PaymentMethod;

use InvalidArgumentException;
use YooKassa\Helpers\Random;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\MonetaryAmount;
use YooKassa\Model\Payment\PaymentMethod\PaymentMethodSberLoan;
use YooKassa\Model\Payment\PaymentMethodType;

/**
 * @internal
 */
class PaymentMethodSberLoanTest extends AbstractTestPaymentMethod
{
    protected function getTestInstance(): PaymentMethodSberLoan
    {
        return new PaymentMethodSberLoan();
    }

    protected function getExpectedType(): string
    {
        return PaymentMethodType::SBER_LOAN;
    }

    /**
     * @dataProvider validLoanOptionDataProvider
     *
     * @param mixed $value
     */
    public function testGetSetLoanOption(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setLoanOption($value);
        self::assertEquals($value, $instance->getLoanOption());
        self::assertEquals($value, $instance->loan_option);

        $instance = $this->getTestInstance();
        $instance->loan_option = $value;
        self::assertEquals($value, $instance->getLoanOption());
        self::assertEquals($value, $instance->loan_option);
    }

    /**
     * @dataProvider invalidLoanOptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLoanOption(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        $instance = $this->getTestInstance();
        $instance->setLoanOption($value);
    }

    /**
     * @dataProvider invalidLoanOptionDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidLoanOption(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);

        $instance = $this->getTestInstance();
        $instance->loan_option = $value;
    }

    /**
     * @dataProvider validDiscountAmountDataProvider
     */
    public function testGetSetDiscountAmount(mixed $value): void
    {
        $instance = $this->getTestInstance();

        $instance->setDiscountAmount($value);
        self::assertSame($value, $instance->getDiscountAmount());
        self::assertSame($value, $instance->discount_amount);
        self::assertSame($value, $instance->discountAmount);

        $instance = $this->getTestInstance();
        $instance->discount_amount = $value;
        self::assertSame($value, $instance->getDiscountAmount());
        self::assertSame($value, $instance->discount_amount);
        self::assertSame($value, $instance->discountAmount);

        $instance = $this->getTestInstance();
        $instance->discountAmount = $value;
        self::assertSame($value, $instance->getDiscountAmount());
        self::assertSame($value, $instance->discount_amount);
        self::assertSame($value, $instance->discountAmount);
    }

    /**
     * @dataProvider invalidDiscountAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidDiscountAmount(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->setDiscountAmount($value);
    }

    /**
     * @dataProvider invalidDiscountAmountDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidDiscountAmount(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->discount_amount = $value;
    }

    public function validLoanOptionDataProvider(): array
    {
        return [
            [null],
            [''],
            ['loan'],
            ['installments_1'],
            ['installments_12'],
            ['installments_36'],
        ];
    }

    public function invalidLoanOptionDataProvider(): array
    {
        return [
            [true],
            ['2345678901234567'],
            ['installments_'],
        ];
    }

    public function validDiscountAmountDataProvider(): array
    {
        $result = [
            [null],
        ];
        for ($i = 0; $i < 10; $i++) {
            $result[] = [new MonetaryAmount(['value' => Random::int(1, 10000), 'currency' => Random::value(CurrencyCode::getEnabledValues())])];
        }
        return $result;
    }

    public function invalidDiscountAmountDataProvider(): array
    {
        return [
            [true],
            ['2345678901234567'],
            ['installments_'],
        ];
    }
}
