<?php

namespace Tests\YooKassa\Request\SelfEmployed;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmation;
use YooKassa\Request\SelfEmployed\SelfEmployedRequestConfirmationFactory;

/**
 * @internal
 */
class ConfirmationFactoryTest extends TestCase
{
    /**
     * @dataProvider validTypeDataProvider
     */
    public function testFactory(string $type): void
    {
        $instance = $this->getTestInstance();
        $confirmation = $instance->factory($type);
        self::assertNotNull($confirmation);
        self::assertInstanceOf(SelfEmployedRequestConfirmation::class, $confirmation);
        self::assertEquals($type, $confirmation->getType());
    }

    /**
     * @dataProvider invalidTypeDataProvider
     *
     * @param mixed $type
     */
    public function testInvalidFactory($type): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->factory($type);
    }

    /**
     * @dataProvider validArrayDataProvider
     */
    public function testFactoryFromArray(array $options): void
    {
        $instance = $this->getTestInstance();
        $confirmation = $instance->factoryFromArray($options);
        self::assertNotNull($confirmation);
        self::assertInstanceOf(SelfEmployedRequestConfirmation::class, $confirmation);

        foreach ($options as $property => $value) {
            self::assertEquals($confirmation->{$property}, $value);
        }

        $type = $options['type'];
        unset($options['type']);
        $confirmation = $instance->factoryFromArray($options, $type);
        self::assertNotNull($confirmation);
        self::assertInstanceOf(SelfEmployedRequestConfirmation::class, $confirmation);

        self::assertEquals($type, $confirmation->getType());
        foreach ($options as $property => $value) {
            self::assertEquals($confirmation->{$property}, $value);
        }
    }

    /**
     * @dataProvider invalidDataArrayDataProvider
     *
     * @param mixed $options
     */
    public function testInvalidFactoryFromArray($options): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = $this->getTestInstance();
        $instance->factoryFromArray($options);
    }

    public static function validTypeDataProvider(): array
    {
        $result = [];
        foreach (SelfEmployedConfirmationType::getValidValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    public static function invalidTypeDataProvider(): array
    {
        return [
            [''],
            [0],
            [1],
            [-1],
            ['5'],
            [Random::str(10)],
        ];
    }

    public static function validArrayDataProvider(): array
    {
        $result = [
            [
                [
                    'type' => SelfEmployedConfirmationType::REDIRECT,
                ],
            ],
        ];
        foreach (SelfEmployedConfirmationType::getValidValues() as $value) {
            $result[] = [['type' => $value]];
        }

        return $result;
    }

    public static function invalidDataArrayDataProvider(): array
    {
        return [
            [[]],
            [['type' => 'test']],
        ];
    }

    protected function getTestInstance(): SelfEmployedRequestConfirmationFactory
    {
        return new SelfEmployedRequestConfirmationFactory();
    }
}
