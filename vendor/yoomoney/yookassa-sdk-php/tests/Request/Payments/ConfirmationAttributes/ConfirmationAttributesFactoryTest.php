<?php

namespace Tests\YooKassa\Request\Payments\ConfirmationAttributes;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Request\Payments\ConfirmationAttributes\AbstractConfirmationAttributes;
use YooKassa\Request\Payments\ConfirmationAttributes\ConfirmationAttributesFactory;

/**
 * @internal
 */
class ConfirmationAttributesFactoryTest extends TestCase
{
    /**
     * @dataProvider validTypeDataProvider
     */
    public function testFactory(string $type): void
    {
        $instance = $this->getTestInstance();
        $confirmation = $instance->factory($type);
        self::assertNotNull($confirmation);
        self::assertInstanceOf(AbstractConfirmationAttributes::class, $confirmation);
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
        self::assertInstanceOf(AbstractConfirmationAttributes::class, $confirmation);

        foreach ($options as $property => $value) {
            self::assertEquals($confirmation->{$property}, $value);
        }

        $type = $options['type'];
        unset($options['type']);
        $confirmation = $instance->factoryFromArray($options, $type);
        self::assertNotNull($confirmation);
        self::assertInstanceOf(AbstractConfirmationAttributes::class, $confirmation);

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

    public static function validTypeDataProvider()
    {
        $result = [];
        foreach (ConfirmationType::getEnabledValues() as $value) {
            $result[] = [$value];
        }

        return $result;
    }

    public static function invalidTypeDataProvider()
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

    public static function validArrayDataProvider()
    {
        $result = [
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'enforce' => false,
                    'returnUrl' => 'https://test.com',
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'enforce' => true,
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'returnUrl' => 'https://test.com',
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                ],
            ],
        ];
        foreach (ConfirmationType::getEnabledValues() as $value) {
            $result[] = [['type' => $value]];
        }

        return $result;
    }

    public static function invalidDataArrayDataProvider()
    {
        return [
            [[]],
            [['type' => 'test']],
        ];
    }

    protected function getTestInstance(): ConfirmationAttributesFactory
    {
        return new ConfirmationAttributesFactory();
    }
}
