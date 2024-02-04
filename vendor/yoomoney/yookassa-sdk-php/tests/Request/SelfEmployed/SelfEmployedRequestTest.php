<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Request\SelfEmployed\SelfEmployedRequest;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestBuilder;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationRedirect;

/**
 * @internal
 */
class SelfEmployedRequestTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testItn($options): void
    {
        $instance = new SelfEmployedRequest();

        $instance->setItn($options['itn']);

        self::assertSame($options['itn'], $instance->getItn());
        self::assertSame($options['itn'], $instance->itn);
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testPhone($options): void
    {
        $instance = new SelfEmployedRequest();

        self::assertFalse($instance->hasPhone());
        self::assertNull($instance->getPhone());
        self::assertNull($instance->phone);

        $expected = $options['phone'];

        $instance->setPhone($options['phone']);
        if (empty($options['phone'])) {
            self::assertFalse($instance->hasPhone());
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            self::assertTrue($instance->hasPhone());
            self::assertSame($expected, $instance->getPhone());
            self::assertSame($expected, $instance->phone);
        }

        $instance->setPhone();
        self::assertFalse($instance->hasPhone());
        self::assertNull($instance->getPhone());
        self::assertNull($instance->phone);

        $instance->phone = $options['phone'];
        if (empty($options['phone'])) {
            self::assertFalse($instance->hasPhone());
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            self::assertTrue($instance->hasPhone());
            self::assertSame($expected, $instance->getPhone());
            self::assertSame($expected, $instance->phone);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testConfirmation($options): void
    {
        $instance = new SelfEmployedRequest();

        self::assertFalse($instance->hasConfirmation());
        self::assertNull($instance->getConfirmation());
        self::assertNull($instance->confirmation);

        $expected = $options['confirmation'];
        if ($expected instanceof SelfEmployedRequestConfirmation) {
            $expected = $expected->toArray();
        }

        $instance->setConfirmation($options['confirmation']);
        if (empty($options['confirmation'])) {
            self::assertFalse($instance->hasConfirmation());
            self::assertNull($instance->getConfirmation());
            self::assertNull($instance->confirmation);
        } else {
            self::assertTrue($instance->hasConfirmation());
            self::assertSame($expected, $instance->getConfirmation()->toArray());
            self::assertSame($expected, $instance->confirmation->toArray());
        }

        $instance->setConfirmation();
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
            self::assertSame($expected, $instance->getConfirmation()->toArray());
            self::assertSame($expected, $instance->confirmation->toArray());
        }
    }

    /**
     * @dataProvider invalidConfirmationDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassName
     */
    public function testSetInvalidConfirmation(mixed $value, string $exceptionClassName): void
    {
        $instance = new SelfEmployedRequest();

        $this->expectException($exceptionClassName);
        $instance->setConfirmation($value);
    }

    public function testValidate(): void
    {
        $instance = new SelfEmployedRequest();

        self::assertFalse($instance->validate());

        $instance->setItn();
        self::assertFalse($instance->validate());
        $instance->setPhone();
        self::assertFalse($instance->validate());
        $instance->setConfirmation(new SelfEmployedRequestConfirmationRedirect());
        self::assertFalse($instance->validate());

        $instance->setItn('529834813422');
        self::assertTrue($instance->validate());
    }

    public function testBuilder(): void
    {
        $builder = SelfEmployedRequest::builder();
        self::assertInstanceOf(SelfEmployedRequestBuilder::class, $builder);
    }

    public static function validDataProvider(): array
    {
        $factory = new SelfEmployedRequestConfirmationFactory();
        $result = [
            [
                [
                    'itn' => Random::str(12, 12, '0123456789'),
                    'phone' => Random::str(4, 15, '0123456789'),
                    'confirmation' => null,
                ],
            ],
            [
                [
                    'itn' => Random::str(12, 12, '0123456789'),
                    'phone' => Random::str(4, 15, '0123456789'),
                    'confirmation' => $factory->factoryFromArray(['type' => Random::value(SelfEmployedConfirmationType::getValidValues())]),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $request = [
                'itn' => Random::str(12, 12, '0123456789'),
                'phone' => Random::str(4, 15, '0123456789'),
                'confirmation' => $factory->factoryFromArray(['type' => Random::value(SelfEmployedConfirmationType::getValidValues())]),
            ];
            $result[] = [$request];
        }

        return $result;
    }

    public static function invalidItnDataProvider(): array
    {
        return [
            [false],
            [true],
        ];
    }

    public static function invalidPhoneDataProvider(): array
    {
        return [
            [false],
            [true],
        ];
    }

    public static function invalidConfirmationDataProvider(): array
    {
        return [
            [Random::str(100), TypeError::class],
            [[], InvalidArgumentException::class],
            [[new \stdClass()], InvalidArgumentException::class],
        ];
    }
}
