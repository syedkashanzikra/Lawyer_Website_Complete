<?php

namespace Tests\YooKassa\Model\Payment;

use PHPUnit\Framework\TestCase;
use YooKassa\Model\Payment\ThreeDSecure;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;

/**
 * @internal
 */
class ThreeDSecureTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $threeDSecure
     */
    public function testConstructor($threeDSecure): void
    {
        $instance = new ThreeDSecure($threeDSecure);

        self::assertEquals($threeDSecure['applied'], $instance->getApplied());
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $threeDSecure
     */
    public function testGetSetApplied($threeDSecure): void
    {
        $instance = new ThreeDSecure($threeDSecure);

        self::assertEquals($threeDSecure['applied'], $instance->getApplied());

        $instance = new ThreeDSecure();

        $instance->setApplied($threeDSecure['applied']);
        self::assertEquals($threeDSecure['applied'], $instance->getApplied());
        self::assertEquals($threeDSecure['applied'], $instance->applied);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidApplied($value, string $exceptionClassName): void
    {
        $instance = new ThreeDSecure();
        $this->expectException($exceptionClassName);
        $instance->setApplied($value);
    }

    public static function validDataProvider(): array
    {
        return [
            [
                'threeDSecure' => [
                    'applied' => true,
                ],
            ],
            [
                'threeDSecure' => [
                    'applied' => false,
                ],
            ],
        ];
    }

    public static function invalidValueDataProvider()
    {
        return [
            ['', EmptyPropertyValueException::class],
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testJsonSerialize(mixed $threeDSecure): void
    {
        if (is_object($threeDSecure)) {
            $threeDSecure = $threeDSecure->jsonSerialize();
        }

        $instance = new ThreeDSecure($threeDSecure);

        self::assertEquals($threeDSecure, $instance->jsonSerialize());
    }
}
