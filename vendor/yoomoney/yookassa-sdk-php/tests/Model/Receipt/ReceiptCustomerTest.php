<?php

namespace Tests\YooKassa\Model\Receipt;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\Receipt\Receipt;
use YooKassa\Model\Receipt\ReceiptCustomer;

/**
 * @internal
 */
class ReceiptCustomerTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetPhone($options): void
    {
        $instance = new ReceiptCustomer();

        $value = !empty($options['customer']['phone'])
               ? $options['customer']['phone']
               : (!empty($options['phone']) ? $options['phone'] : null);

        $instance->setPhone($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            self::assertEquals($value, $instance->getPhone());
            self::assertEquals($value, $instance->phone);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterPhone($options): void
    {
        $instance = new ReceiptCustomer();

        $value = !empty($options['customer']['phone'])
               ? $options['customer']['phone']
               : (!empty($options['phone']) ? $options['phone'] : null);

        $instance->phone = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getPhone());
            self::assertNull($instance->phone);
        } else {
            self::assertEquals($value, $instance->getPhone());
            self::assertEquals($value, $instance->phone);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testGetSetEmail($options): void
    {
        $instance = new ReceiptCustomer();

        $value = !empty($options['customer']['email'])
               ? $options['customer']['email']
               : (!empty($options['email']) ? $options['email'] : null);

        $instance->setEmail($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getEmail());
            self::assertNull($instance->email);
        } else {
            self::assertEquals($value, $instance->getEmail());
            self::assertEquals($value, $instance->email);
        }
    }

    /**
     * @dataProvider validDataProvider
     *
     * @param mixed $options
     */
    public function testSetterEmail($options): void
    {
        $instance = new ReceiptCustomer();

        $value = !empty($options['customer']['email'])
               ? $options['customer']['email']
               : (!empty($options['email']) ? $options['email'] : null);

        $instance->email = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getEmail());
            self::assertNull($instance->email);
        } else {
            self::assertEquals($value, $instance->getEmail());
            self::assertEquals($value, $instance->email);
        }
    }

    /**
     * @dataProvider validFullNameProvider
     *
     * @param mixed $value
     */
    public function testGetSetFullName($value): void
    {
        $instance = new ReceiptCustomer();

        $instance->setFullName($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getFullName());
            self::assertNull($instance->fullName);
            self::assertNull($instance->full_name);
        } else {
            self::assertEquals($value, $instance->getFullName());
            self::assertEquals($value, $instance->fullName);
            self::assertEquals($value, $instance->full_name);
        }
    }

    /**
     * @dataProvider validFullNameProvider
     *
     * @param mixed $value
     */
    public function testSetterFullName($value): void
    {
        $instance = new ReceiptCustomer();

        $instance->fullName = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getFullName());
            self::assertNull($instance->fullName);
            self::assertNull($instance->full_name);
        } else {
            self::assertEquals($value, $instance->getFullName());
            self::assertEquals($value, $instance->fullName);
            self::assertEquals($value, $instance->full_name);
        }
    }

    /**
     * @dataProvider validFullNameProvider
     *
     * @param mixed $value
     */
    public function testSetterSnakeFullName($value): void
    {
        $instance = new ReceiptCustomer();

        $instance->full_name = $value;
        if (null === $value || '' === $value) {
            self::assertNull($instance->getFullName());
            self::assertNull($instance->fullName);
            self::assertNull($instance->full_name);
        } else {
            self::assertEquals($value, $instance->getFullName());
            self::assertEquals($value, $instance->fullName);
            self::assertEquals($value, $instance->full_name);
        }
    }

    public static function validFullNameProvider()
    {
        return [
            [null],
            [''],
            [Random::str(1)],
            [Random::str(1, 256)],
            [Random::str(256)],
        ];
    }

    /**
     * @dataProvider invalidFullNameProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidFullName($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptCustomer();
        $instance->setFullName($value);
    }

    public static function invalidFullNameProvider()
    {
        return [
            [Random::str(257)],
        ];
    }

    /**
     * @dataProvider validInnProvider
     *
     * @param mixed $value
     */
    public function testGetSetInn($value): void
    {
        $instance = new ReceiptCustomer();

        $instance->setInn($value);
        if (null === $value || '' === $value) {
            self::assertNull($instance->getInn());
            self::assertNull($instance->inn);
        } else {
            self::assertEquals($value, $instance->getInn());
            self::assertEquals($value, $instance->inn);
        }
    }

    /**
     * @dataProvider validInnProvider
     *
     * @param mixed $options
     */
    public function testSetterInn($options): void
    {
        $instance = new ReceiptCustomer();

        $instance->inn = $options;
        if (null === $options || '' === $options) {
            self::assertNull($instance->getInn());
            self::assertNull($instance->inn);
        } else {
            self::assertEquals($options, $instance->getInn());
            self::assertEquals($options, $instance->inn);
        }
    }

    public static function validInnProvider()
    {
        return [
            [null],
            [''],
            ['1234567890'],
            ['123456789012'],
            [Random::str(10, 10, '1234567890')],
            [Random::str(12, 12, '1234567890')],
        ];
    }

    /**
     * @dataProvider invalidInnProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidInn($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new ReceiptCustomer();
        $instance->setInn($value);
    }

    public static function invalidInnProvider()
    {
        return [
            [true],
            ['123456789'],
            ['12345678901'],
            ['1234567890123'],
            [Random::str(1)],
            [Random::str(10, 10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')],
            [Random::str(12, 12, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ')],
            [Random::str(13, 13, '1234567890')],
        ];
    }

    public static function validDataProvider()
    {
        return [
            [
                [
                    'customer' => [],
                ],
            ],
            [
                [
                    'customer' => [],
                    'phone' => Random::str(10, 10, '1234567890'),
                    'email' => uniqid('', true) . '@' . uniqid('', true),
                ],
            ],
            [
                [
                    'customer' => [
                        'phone' => Random::str(10, 10, '1234567890'),
                        'email' => uniqid('', true) . '@' . uniqid('', true),
                    ],
                ],
            ],
            [
                [
                    'customer' => [
                        'full_name' => Random::str(1, 256),
                        'inn' => Random::str(12, 12, '1234567890'),
                    ],
                    'phone' => Random::str(10, 10, '1234567890'),
                    'email' => uniqid('', true) . '@' . uniqid('', true),
                ],
            ],
            [
                [
                    'customer' => [
                        'full_name' => Random::str(1, 256),
                        'phone' => Random::str(10, 10, '1234567890'),
                        'email' => uniqid('', true) . '@' . uniqid('', true),
                        'inn' => Random::str(10, 10, '1234567890'),
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider fromArrayDataProvider
     */
    public function testCustomerFromArray(mixed $source, mixed $expected): void
    {
        $receipt = new Receipt();
        $receipt->fromArray($source);

        if (!empty($expected)) {
            foreach ($expected->jsonSerialize() as $property => $value) {
                self::assertEquals($value, $expected->offsetGet($property));
            }
        } else {
            self::assertEquals(true, $receipt->getCustomer()->isEmpty());
        }
    }

    public static function fromArrayDataProvider()
    {
        $customer = new ReceiptCustomer();
        $customer->setFullName('John Doe');
        $customer->setEmail('johndoe@yoomoney.ru');
        $customer->setPhone('+79000000000');
        $customer->setInn('6321341814');

        return [
            [
                [],
                null,
            ],

            [
                [
                    'customer' => [
                        'fullName' => 'John Doe',
                        'email' => 'johndoe@yoomoney.ru',
                        'phone' => '79000000000',
                        'inn' => '6321341814',
                    ],
                ],

                $customer,
            ],

            [
                [
                    'customer' => [
                        'full_name' => 'John Doe',
                        'inn' => '6321341814',
                        'email' => 'johndoe@yoomoney.ru',
                        'phone' => '79000000000',
                    ],
                ],

                $customer,
            ],

            [
                [
                    'customer' => [
                        'fullName' => 'John Doe',
                        'inn' => '6321341814',
                    ],
                    'email' => 'johndoe@yoomoney.ru',
                    'phone' => '79000000000',
                ],

                $customer,
            ],

            [
                [
                    'customer' => [
                        'full_name' => 'John Doe',
                        'inn' => '6321341814',
                        'email' => 'johndoe@yoomoney.ru',
                    ],
                    'phone' => '79000000000',
                ],

                $customer,
            ],
            [
                [
                    'customer' => [
                        'fullName' => 'John Doe',
                        'inn' => '6321341814',
                        'phone' => '79000000000',
                    ],
                    'email' => 'johndoe@yoomoney.ru',
                ],

                $customer,
            ],
            [
                [
                    'customer' => [
                        'full_name' => 'John Doe',
                        'inn' => '6321341814',
                        'phone' => '79000000000',
                        'email' => 'johndoe@yoomoney.ru',
                    ],
                    'email' => 'johndoeOld@yoomoney.ru',
                ],

                $customer,
            ],
            [
                [
                    'customer' => [
                        'fullName' => 'John Doe',
                        'inn' => '6321341814',
                        'phone' => '79000000000',
                        'email' => 'johndoe@yoomoney.ru',
                    ],
                    'phone' => '79111111111',
                ],

                $customer,
            ],
            [
                [
                    'customer' => [
                        'full_name' => 'John Doe',
                        'inn' => '6321341814',
                        'phone' => '79000000000',
                        'email' => 'johndoe@yoomoney.ru',
                    ],
                    'phone' => '79111111111',
                    'email' => 'johndoeOld@yoomoney.ru',
                ],

                $customer,
            ],
        ];
    }
}
