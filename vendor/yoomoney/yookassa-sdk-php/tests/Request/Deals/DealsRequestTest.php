<?php

namespace Tests\YooKassa\Request\Deals;

use DateTime;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\Deal\DealStatus;
use YooKassa\Model\Deal\SafeDeal;
use YooKassa\Request\Deals\DealsRequest;
use YooKassa\Request\Deals\DealsRequestBuilder;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class DealsRequestTest extends TestCase
{
    /**
     * @dataProvider validCursorDataProvider
     *
     * @param mixed $value
     */
    public function testCursor($value): void
    {
        $this->getterAndSetterTest($value, 'cursor', null === $value ? '' : (string) $value);
    }

    /**
     * @dataProvider validFullTextSearchDataProvider
     *
     * @param mixed $value
     */
    public function testFullTextSearch($value): void
    {
        $this->getterAndSetterTest($value, 'fullTextSearch', $value);
    }

    /**
     * @dataProvider invalidFullTextSearchDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidFullTextSearch(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setFullTextSearch($value);
    }

    /**
     * @dataProvider invalidFullTextSearchDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidFullTextSearch(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->full_text_search = $value;
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
            'expiresAtGte',
            'expiresAtGt',
            'expiresAtLte',
            'expiresAtLt',
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
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidCreatedAtGte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setCreatedAtGte($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidCreatedAtGte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->createdAtGte = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidCreatedAtGt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setCreatedAtGt($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidCreatedAtGt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->createdAtGt = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidCreatedAtLte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setCreatedAtLte($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidCreatedAtLte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->createdAtLte = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidCreatedAtLt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setCreatedAtLt($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidCreatedAtLt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->createdAtLt = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidExpiresAtGte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setExpiresAtGte($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidExpiresAtGte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->expiresAtGte = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidExpiresAtGt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setExpiresAtGt($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidExpiresAtGt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->expiresAtGt = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidExpiresAtLte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setCreatedAtLte($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidExpiresAtLte(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->expiresAtLte = $value;
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidExpiresAtLt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setExpiresAtLt($value);
    }

    /**
     * @dataProvider invalidDataTimeDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidExpiresAtLt(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->expiresAtLt = $value;
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
     * @param string $exceptionClassName
     */
    public function testSetInvalidLimit(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->setLimit($value);
    }

    /**
     * @dataProvider invalidLimitDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetterInvalidLimit(mixed $value, string $exceptionClassName): void
    {
        $instance = $this->getTestInstance();

        $this->expectException($exceptionClassName);
        $instance->limit = $value;
    }

    /**
     * @dataProvider validStatusDataProvider
     *
     * @param mixed $value
     */
    public function testStatus($value): void
    {
        $this->getterAndSetterTest($value, 'status', (string) $value);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getTestInstance()->setStatus($value);
    }

    /**
     * @return void
     */
    public function testValidate(): void
    {
        $instance = new DealsRequest();
        self::assertTrue($instance->validate());
    }

    /**
     * @return void
     */
    public function testBuilder(): void
    {
        $builder = DealsRequest::builder();
        self::assertInstanceOf(DealsRequestBuilder::class, $builder);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validCursorDataProvider(): array
    {
        return [
            [Random::str(1)],
            [Random::str(2, 64)],
            [new StringObject(Random::str(1))],
            [new StringObject(Random::str(2, 64))],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validFullTextSearchDataProvider(): array
    {
        return [
            [null],
            ['1234'],
            [Random::str(DealsRequest::MIN_LENGTH_DESCRIPTION, SafeDeal::MAX_LENGTH_DESCRIPTION)],
            [new StringObject(Random::str(DealsRequest::MIN_LENGTH_DESCRIPTION, SafeDeal::MAX_LENGTH_DESCRIPTION))],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function validIdDataProvider(): array
    {
        return [
            [null],
            [''],
            ['123'],
            [Random::str(1, 64)],
            [new StringObject(Random::str(1, 64))],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validDateDataProvider(): array
    {
        return [
            [date(YOOKASSA_DATE, Random::int(0, time()))],
            [new DateTime()],
        ];
    }

    /**
     * @return array
     */
    public static function validStatusDataProvider(): array
    {
        $result = [];
        foreach (DealStatus::getValidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function validLimitDataProvider(): array
    {
        return [
            [10],
            [Random::int(1, DealsRequest::MAX_LIMIT_VALUE)],
        ];
    }

    /**
     * @return array
     */
    public function invalidIdDataProvider(): array
    {
        return [
            [[]],
            [new stdClass()],
            [true],
            [false],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidDataProvider(): array
    {
        return [
            [Random::str(10)],
            [Random::bytes(10)],
            [-1],
            [DealsRequest::MAX_LIMIT_VALUE + 1],
        ];
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public static function invalidFullTextSearchDataProvider(): array
    {
        return [
            [true, InvalidPropertyValueException::class],
            [Random::str(DealsRequest::MIN_LENGTH_DESCRIPTION - 1), InvalidPropertyValueException::class],
            [new StringObject(Random::str(SafeDeal::MAX_LENGTH_DESCRIPTION + 1)), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidDateDataProvider(): array
    {
        return [
            [true],
            [false],
            [[]],
            [new stdClass()],
            [Random::str(35)],
            [Random::str(37)],
            [new StringObject(Random::str(10))],
            [-123],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidDataTimeDataProvider(): array
    {
        return [
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
            [Random::int(), InvalidPropertyValueException::class],
            [Random::str(100), InvalidPropertyValueException::class],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidLimitDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', TypeError::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
            [Random::int(DealsRequest::MAX_LIMIT_VALUE + 1), InvalidPropertyValueException::class],
            [Random::str(100), TypeError::class],
        ];
    }

    /**
     * @return DealsRequest
     */
    protected function getTestInstance(): DealsRequest
    {
        return new DealsRequest();
    }

    /**
     * @param $value
     * @param string $property
     * @param $expected
     * @param bool $testHas
     * @return void
     */
    private function getterAndSetterTest(mixed $value, string $property, mixed $expected, bool $testHas = true): void
    {
        $getter = 'get' . ucfirst($property);
        $setter = 'set' . ucfirst($property);
        $has = 'has' . ucfirst($property);

        $instance = $this->getTestInstance();

        if ($testHas && !$instance->{$has}()) {
            self::assertFalse($instance->{$has}());
            self::assertNull($instance->{$getter}());
            self::assertNull($instance->{$property});
        }

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
