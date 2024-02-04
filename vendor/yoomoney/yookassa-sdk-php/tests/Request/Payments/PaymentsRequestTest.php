<?php

namespace Tests\YooKassa\Request\Payments;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\PaymentStatus;
use YooKassa\Request\Payments\PaymentsRequest;
use YooKassa\Request\Payments\PaymentsRequestBuilder;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueTypeException;

/**
 * @internal
 */
class PaymentsRequestTest extends TestCase
{
    /**
     * @dataProvider validNetPageDataProvider
     *
     * @param mixed $value
     */
    public function testCursor($value): void
    {
        $this->getterAndSetterTest($value, 'cursor', null === $value ? '' : (string) $value);
    }

    /**
     * @dataProvider validPaymentMethodDataProvider
     *
     * @param mixed $value
     */
    public function testPaymentMethod($value): void
    {
        $this->getterAndSetterTest($value, 'paymentMethod', $value);
    }

    /**
     * @dataProvider invalidPaymentMethodDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidPaymentMethod($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setPaymentMethod($value);
    }

    /**
     * @dataProvider validDateDataProvider
     *
     * @param mixed $value
     */
    public function testDateMethods($value): void
    {
        $properties = [
            'createdAtGte',
            'createdAtGt',
            'createdAtLte',
            'createdAtLt',
            'capturedAtGte',
            'capturedAtGt',
            'capturedAtLte',
            'capturedAtLt',
        ];
        $expected = null;
        if ($value instanceof DateTime) {
            $expected = $value->format(YOOKASSA_DATE);
        } elseif (is_numeric($value)) {
            $expected = date(YOOKASSA_DATE, $value);
        } else {
            $expected = $value;
        }
        foreach ($properties as $property) {
            $this->getterAndSetterTest($value, $property, empty($expected) ? null : new DateTime($expected));
        }
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedAtGte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCreatedAtGte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedAtGt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCreatedAtGt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedAtLte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCreatedAtLte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedAtLt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCreatedAtLt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCapturedAtGte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCapturedAtGte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCapturedAtGt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCapturedAtGt($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCapturedAtLte($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCapturedAtLte($value);
    }

    /**
     * @dataProvider invalidDateDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCapturedAtLt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setCapturedAtLt($value);
    }

    /**
     * @dataProvider validLimitDataProvider
     *
     * @param mixed $value
     */
    public function testLimit($value): void
    {
        $this->getterAndSetterTest($value, 'limit', $value);
    }

    /**
     * @dataProvider invalidLimitDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidLimit($value, $exception): void
    {
        $this->expectException($exception);
        $this->getTestInstance()->setLimit($value);
    }

    /**
     * @dataProvider validStatusDataProvider
     *
     * @param mixed $value
     */
    public function testStatus($value): void
    {
        $this->getterAndSetterTest($value, 'status', null === $value ? '' : (string) $value);
    }

    /**
     * @dataProvider invalidStatusDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setStatus($value);
    }

    public function testValidate(): void
    {
        $instance = new PaymentsRequest();
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = PaymentsRequest::builder();
        self::assertInstanceOf(PaymentsRequestBuilder::class, $builder);
    }

    public static function validNetPageDataProvider()
    {
        return [
            [''],
            [null],
            [Random::str(1)],
            [Random::str(2, 64)],
            [new StringObject(Random::str(1))],
            [new StringObject(Random::str(2, 64))],
        ];
    }

    public static function validPaymentMethodDataProvider()
    {
        $result = [
            [null],
            [''],
        ];
        foreach (PaymentMethodType::getValidValues() as $value) {
            $result[] = [$value];
            $result[] = [new StringObject($value)];
        }

        return $result;
    }

    public function validIdDataProvider()
    {
        return [
            [null],
            [''],
            ['123'],
            [Random::str(1, 64)],
            [new StringObject(Random::str(1, 64))],
        ];
    }

    public static function validDateDataProvider()
    {
        return [
            [null],
            [''],
            [date(YOOKASSA_DATE, Random::int(0, time()))],
            [new DateTime()],
        ];
    }

    public static function validStatusDataProvider()
    {
        $result = [
            [null],
            [''],
        ];
        foreach (PaymentStatus::getValidValues() as $value) {
            $result[] = [$value];
            $result[] = [new StringObject($value)];
        }

        return $result;
    }

    public static function validLimitDataProvider()
    {
        return [
            [null],
            [Random::int(1, PaymentsRequest::MAX_LIMIT_VALUE)],
        ];
    }

    public function invalidIdDataProvider()
    {
        return [
            [[]],
            [new stdClass()],
            [true],
            [false],
        ];
    }

    public static function invalidLimitDataProvider()
    {
        return [
            [new stdClass(), InvalidPropertyValueTypeException::class],
            [Random::str(10), InvalidPropertyValueTypeException::class],
            [Random::bytes(10), InvalidPropertyValueTypeException::class],
            [-1, InvalidPropertyValueException::class],
            [PaymentsRequest::MAX_LIMIT_VALUE + 1, InvalidPropertyValueException::class],
        ];
    }

    public static function invalidStatusDataProvider()
    {
        return [
            [Random::str(10)],
        ];
    }

    public static function invalidPaymentMethodDataProvider()
    {
        return [
            [true],
            [Random::str(35)],
            [Random::str(37)],
            [new StringObject(Random::str(10))],
        ];
    }

    public static function invalidDateDataProvider()
    {
        return [
            [true],
            [false],
            [new stdClass()],
            [Random::str(35)],
            [Random::str(37)],
            [new StringObject(Random::str(10))],
            [-123],
        ];
    }

    public static function invalidPageDataProvider()
    {
        return [
            [[]],
            [new stdClass()],
            [true],
            [false],
        ];
    }

    protected function getTestInstance(): PaymentsRequest
    {
        return new PaymentsRequest();
    }

    private function getterAndSetterTest($value, $property, $expected, $testHas = true): void
    {
        $getter = 'get' . ucfirst($property);
        $setter = 'set' . ucfirst($property);
        $has = 'has' . ucfirst($property);

        $instance = $this->getTestInstance();

        if ($testHas) {
            self::assertFalse($instance->{$has}());
        }
        self::assertNull($instance->{$getter}());
        self::assertNull($instance->{$property});

        $instance->{$setter}($value);
        if (null === $value || '' === $value) {
            if ($testHas) {
                self::assertFalse($instance->{$has}());
            }
            self::assertNull($instance->{$getter}());
            self::assertNull($instance->{$property});
        } else {
            if ($testHas) {
                self::assertTrue($instance->{$has}());
            }
            if ($expected instanceof DateTime) {
                self::assertEquals($expected->getTimestamp(), $instance->{$getter}()->getTimestamp());
                self::assertEquals($expected->getTimestamp(), $instance->{$property}->getTimestamp());
            } else {
                self::assertEquals($expected, $instance->{$getter}());
                self::assertEquals($expected, $instance->{$property});
            }
        }

        $instance->{$setter}(null);
        if ($testHas) {
            self::assertFalse($instance->{$has}());
        }
        self::assertNull($instance->{$getter}());
        self::assertNull($instance->{$property});

        $instance->{$property} = $value;
        if (null === $value || '' === $value) {
            if ($testHas) {
                self::assertFalse($instance->{$has}());
            }
            self::assertNull($instance->{$getter}());
            self::assertNull($instance->{$property});
        } else {
            if ($testHas) {
                self::assertTrue($instance->{$has}());
            }
            if ($expected instanceof DateTime) {
                self::assertEquals($expected->getTimestamp(), $instance->{$getter}()->getTimestamp());
                self::assertEquals($expected->getTimestamp(), $instance->{$property}->getTimestamp());
            } else {
                self::assertEquals($expected, $instance->{$getter}());
                self::assertEquals($expected, $instance->{$property});
            }
        }
    }
}
