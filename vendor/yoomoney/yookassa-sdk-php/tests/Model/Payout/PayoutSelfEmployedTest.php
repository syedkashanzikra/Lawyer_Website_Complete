<?php

namespace Tests\YooKassa\Model\Payout;

use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payout\PayoutSelfEmployed;
use YooKassa\Validator\Exceptions\EmptyPropertyValueException;
use YooKassa\Validator\Exceptions\InvalidPropertyValueException;

/**
 * @internal
 */
class PayoutSelfEmployedTest extends TestCase
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
     * @return array
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        $result = [];

        for ($i = 0; $i < 10; $i++) {
            $deal = [
                'id' => Random::str(PayoutSelfEmployed::MIN_LENGTH_ID, PayoutSelfEmployed::MAX_LENGTH_ID),
            ];
            $result[] = [$deal];
        }

        return $result;
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassBic
     */
    public function testSetInvalidId(mixed $value, string $exceptionClassBic): void
    {
        $instance = new PayoutSelfEmployed();

        $this->expectException($exceptionClassBic);
        $instance->setId($value);
    }

    /**
     * @dataProvider invalidIdDataProvider
     *
     * @param mixed $value
     * @param string $exceptionClassBic
     */
    public function testSetterInvalidId(mixed $value, string $exceptionClassBic): void
    {
        $instance = new PayoutSelfEmployed();

        $this->expectException($exceptionClassBic);
        $instance->id = $value;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function invalidIdDataProvider(): array
    {
        return [
            [null, EmptyPropertyValueException::class],
            ['', EmptyPropertyValueException::class],
            [Random::str(PayoutSelfEmployed::MIN_LENGTH_ID - 1), InvalidPropertyValueException::class],
            [Random::str(PayoutSelfEmployed::MAX_LENGTH_ID + 1), InvalidPropertyValueException::class],
            [Random::int(), InvalidPropertyValueException::class],
            [[], TypeError::class],
            [new \stdClass(), TypeError::class],
        ];
    }

    /**
     * @param mixed $options
     */
    protected function getTestInstance($options): PayoutSelfEmployed
    {
        return new PayoutSelfEmployed($options);
    }
}
