<?php

namespace Tests\YooKassa\Model\Payment\Confirmation;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\Confirmation\AbstractConfirmation;
use YooKassa\Model\Payment\Confirmation\ConfirmationFactory;
use YooKassa\Model\Payment\ConfirmationType;

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
        self::assertInstanceOf(AbstractConfirmation::class, $confirmation);
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
        self::assertInstanceOf(AbstractConfirmation::class, $confirmation);

        foreach ($options as $property => $value) {
            self::assertEquals($confirmation->{$property}, $value);
        }

        $type = $options['type'];
        unset($options['type']);
        $confirmation = $instance->factoryFromArray($options, $type);
        self::assertNotNull($confirmation);
        self::assertInstanceOf(AbstractConfirmation::class, $confirmation);

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
        foreach (ConfirmationType::getValidValues() as $value) {
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
        $url = [
            'https://shop.store/testurl?pr=xXxXxX',
            'https://test.ru',
        ];
        $result = [
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'enforce' => false,
                    'returnUrl' => Random::value($url),
                    'confirmationUrl' => Random::value($url),
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'enforce' => true,
                    'returnUrl' => Random::value($url),
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'returnUrl' => Random::value($url),
                    'confirmationUrl' => Random::value($url),
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'confirmationUrl' => Random::value($url),
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'returnUrl' => Random::value($url),
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                    'enforce' => Random::bool(),
                ],
            ],
            [
                [
                    'type' => ConfirmationType::REDIRECT,
                ],
            ],
            [
                [
                    'type' => ConfirmationType::QR,
                    'confirmation_data' => Random::str(30),
                ],
            ],
        ];
        foreach (ConfirmationType::getValidValues() as $value) {
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

    protected function getTestInstance(): ConfirmationFactory
    {
        return new ConfirmationFactory();
    }
}
