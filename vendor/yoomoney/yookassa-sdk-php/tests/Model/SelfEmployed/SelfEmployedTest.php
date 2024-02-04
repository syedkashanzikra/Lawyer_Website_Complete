<?php

namespace Tests\YooKassa\Model\SelfEmployed;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\SelfEmployed\SelfEmployed;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationFactory;
use YooKassa\Model\SelfEmployed\SelfEmployedConfirmationType;
use YooKassa\Model\SelfEmployed\SelfEmployedStatus;

/**
 * @internal
 */
class SelfEmployedTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new SelfEmployed();
        $instance->id = $options['id'];
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->setId($value['id']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidId($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetStatus(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new SelfEmployed();
        $instance->status = $options['status'];
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->setStatus($value['status']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidStatus($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetTest(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setTest($options['test']);
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);

        $instance = new SelfEmployed();
        $instance->test = $options['test'];
        self::assertSame($options['test'], $instance->getTest());
        self::assertSame($options['test'], $instance->test);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetPhone(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setPhone($options['phone']);
        self::assertSame($options['phone'], $instance->getPhone());
        self::assertSame($options['phone'], $instance->phone);

        $instance = new SelfEmployed();
        $instance->phone = $options['phone'];
        self::assertSame($options['phone'], $instance->getPhone());
        self::assertSame($options['phone'], $instance->phone);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetItn(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setItn($options['itn']);
        self::assertSame($options['itn'], $instance->getItn());
        self::assertSame($options['itn'], $instance->itn);

        $instance = new SelfEmployed();
        $instance->itn = $options['itn'];
        self::assertSame($options['itn'], $instance->getItn());
        self::assertSame($options['itn'], $instance->itn);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCreatedAt(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new SelfEmployed();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new SelfEmployed();
        $instance->created_at = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidCreatedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->setCreatedAt($value['created_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCreatedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->createdAt = $value['created_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeCreatedAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetConfirmation(array $options): void
    {
        $instance = new SelfEmployed();

        $instance->setConfirmation($options['confirmation']);
        if (is_array($options['confirmation'])) {
            self::assertSame($options['confirmation'], $instance->getConfirmation()->toArray());
            self::assertSame($options['confirmation'], $instance->confirmation->toArray());
        } else {
            self::assertSame($options['confirmation'], $instance->getConfirmation());
            self::assertSame($options['confirmation'], $instance->confirmation);
        }

        $instance = new SelfEmployed();
        $instance->confirmation = $options['confirmation'];
        if (is_array($options['confirmation'])) {
            self::assertSame($options['confirmation'], $instance->getConfirmation()->toArray());
            self::assertSame($options['confirmation'], $instance->confirmation->toArray());
        } else {
            self::assertSame($options['confirmation'], $instance->getConfirmation());
            self::assertSame($options['confirmation'], $instance->confirmation);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidConfirmation($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SelfEmployed();
        $instance->confirmation = $value['confirmation'];
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $confirmTypes = SelfEmployedConfirmationType::getValidValues();
        $confirmFactory = new SelfEmployedConfirmationFactory();

        $result[] = [
            [
                'id' => Random::str(36, 50),
                'status' => Random::value(SelfEmployedStatus::getValidValues()),
                'test' => Random::bool(),
                'itn' => null,
                'phone' => Random::str(11, 11, '0123456789'),
                'created_at' => date(YOOKASSA_DATE, Random::int(111111111, time())),
                'confirmation' => ['type' => Random::value($confirmTypes)],
            ],
        ];
        $result[] = [
            [
                'id' => Random::str(36, 50),
                'status' => Random::value(SelfEmployedStatus::getValidValues()),
                'test' => Random::bool(),
                'itn' => Random::str(12, '0123456789'),
                'phone' => null,
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'confirmation' => null,
            ],
        ];

        for ($i = 0; $i < 20; $i++) {
            $payment = [
                'id' => Random::str(36, 50),
                'status' => Random::value(SelfEmployedStatus::getValidValues()),
                'test' => Random::bool(),
                'itn' => Random::str(12, '0123456789'),
                'phone' => Random::str(11, 11, '0123456789'),
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'confirmation' => $confirmFactory->factory(Random::value($confirmTypes)),
            ];
            $result[] = [$payment];
        }

        return $result;
    }

    public static function invalidDataProvider(): array
    {
        $result = [
            [
                [
                    'id' => Random::str(60),
                    'test' => null,
                    'status' => Random::str(10),
                    'itn' => new stdClass(),
                    'phone' => [],
                    'created_at' => null,
                    'confirmation' => new stdClass(),
                ],
            ],
            [
                [
                    'id' => '',
                    'test' => 'null',
                    'status' => '',
                    'itn' => [],
                    'phone' => new stdClass(),
                    'created_at' => Random::str(10),
                    'confirmation' => new stdClass(),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $selfEmployed = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(51, 64)),
                'test' => $i % 2 ? Random::str(10) : new stdClass(),
                'status' => Random::str(1, 35),
                'phone' => $i % 2 ? new stdClass() : [],
                'itn' => $i % 2 ? new stdClass() : [],
                'created_at' => 0 === $i ? '23423-234-32' : -Random::int(),
                'confirmation' => Random::value([
                    new DateTime(),
                    new stdClass(),
                ]),
            ];
            $result[] = [$selfEmployed];
        }

        return $result;
    }
}
