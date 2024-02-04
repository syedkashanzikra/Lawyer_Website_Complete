<?php

namespace Tests\YooKassa\Model\PersonalData;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use YooKassa\Helpers\Random;
use YooKassa\Model\Metadata;
use YooKassa\Model\PersonalData\PersonalData;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetails;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsPartyCode;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsReasonCode;
use YooKassa\Model\PersonalData\PersonalDataStatus;
use YooKassa\Model\PersonalData\PersonalDataType;

/**
 * @internal
 */
class PersonalDataTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetId(array $options): void
    {
        $instance = new PersonalData();

        $instance->setId($options['id']);
        self::assertEquals($options['id'], $instance->getId());
        self::assertEquals($options['id'], $instance->id);

        $instance = new PersonalData();
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
        $instance = new PersonalData();
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
        $instance = new PersonalData();
        $instance->id = $value['id'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetStatus(array $options): void
    {
        $instance = new PersonalData();

        $instance->setStatus($options['status']);
        self::assertEquals($options['status'], $instance->getStatus());
        self::assertEquals($options['status'], $instance->status);

        $instance = new PersonalData();
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
        $instance = new PersonalData();
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
        $instance = new PersonalData();
        $instance->status = $value['status'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetType(array $options): void
    {
        $instance = new PersonalData();

        $instance->setType($options['type']);
        self::assertSame($options['type'], $instance->getType());
        self::assertSame($options['type'], $instance->type);

        $instance = new PersonalData();
        $instance->type = $options['type'];
        self::assertSame($options['type'], $instance->getType());
        self::assertSame($options['type'], $instance->type);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->setType($value['type']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidType($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->type = $value['type'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCreatedAt(array $options): void
    {
        $instance = new PersonalData();

        $instance->setCreatedAt($options['created_at']);
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new PersonalData();
        $instance->createdAt = $options['created_at'];
        self::assertSame($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->createdAt->format(YOOKASSA_DATE));
        self::assertSame($options['created_at'], $instance->created_at->format(YOOKASSA_DATE));

        $instance = new PersonalData();
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
        $instance = new PersonalData();
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
        $instance = new PersonalData();
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
        $instance = new PersonalData();
        $instance->created_at = $value['created_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetExpiresAt(array $options): void
    {
        $instance = new PersonalData();

        $instance->setExpiresAt($options['expires_at']);
        if (!empty($options['expires_at'])) {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        } else {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expiresAt);
            self::assertNull($instance->expires_at);
        }

        $instance = new PersonalData();
        $instance->expiresAt = $options['expires_at'];
        if (!empty($options['expires_at'])) {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        } else {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expiresAt);
            self::assertNull($instance->expires_at);
        }

        $instance = new PersonalData();
        $instance->expires_at = $options['expires_at'];
        if (!empty($options['expires_at'])) {
            self::assertSame($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expiresAt->format(YOOKASSA_DATE));
            self::assertSame($options['expires_at'], $instance->expires_at->format(YOOKASSA_DATE));
        } else {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expiresAt);
            self::assertNull($instance->expires_at);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->setExpiresAt($value['expires_at']);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->expiresAt = $value['expires_at'];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidSnakeExpiresAt($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->expires_at = $value['expires_at'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetCancellationDetails(array $options): void
    {
        $instance = new PersonalData();

        $instance->setCancellationDetails($options['cancellation_details']);
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new PersonalData();
        $instance->cancellationDetails = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);

        $instance = new PersonalData();
        $instance->cancellation_details = $options['cancellation_details'];
        self::assertSame($options['cancellation_details'], $instance->getCancellationDetails());
        self::assertSame($options['cancellation_details'], $instance->cancellationDetails);
        self::assertSame($options['cancellation_details'], $instance->cancellation_details);
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidCancellationDetails($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->cancellation_details = $value['cancellation_details'];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetMetadata(array $options): void
    {
        $instance = new PersonalData();

        if (is_array($options['metadata'])) {
            $instance->setMetadata($options['metadata']);
            self::assertSame($options['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($options['metadata'], $instance->metadata->toArray());

            $instance = new PersonalData();
            $instance->metadata = $options['metadata'];
            self::assertSame($options['metadata'], $instance->getMetadata()->toArray());
            self::assertSame($options['metadata'], $instance->metadata->toArray());
        } elseif ($options['metadata'] instanceof Metadata || empty($options['metadata'])) {
            $instance->setMetadata($options['metadata']);
            self::assertSame($options['metadata'], $instance->getMetadata());
            self::assertSame($options['metadata'], $instance->metadata);

            $instance = new PersonalData();
            $instance->metadata = $options['metadata'];
            self::assertSame($options['metadata'], $instance->getMetadata());
            self::assertSame($options['metadata'], $instance->metadata);
        }
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidMetadata($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new PersonalData();
        $instance->metadata = $value['metadata'];
    }

    public static function validDataProvider(): array
    {
        $result = [];
        $cancellationDetailsParties = PersonalDataCancellationDetailsPartyCode::getValidValues();
        $countCancellationDetailsParties = count($cancellationDetailsParties);
        $cancellationDetailsReasons = PersonalDataCancellationDetailsReasonCode::getValidValues();
        $countCancellationDetailsReasons = count($cancellationDetailsReasons);

        $result[] = [
            [
                'id' => Random::str(36, 50),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, Random::int(111111111, time())),
                'expires_at' => null,
                'metadata' => ['order_id' => '37'],
                'cancellation_details' => new PersonalDataCancellationDetails([
                    'party' => Random::value($cancellationDetailsParties),
                    'reason' => Random::value($cancellationDetailsReasons),
                ]),
            ],
        ];
        $result[] = [
            [
                'id' => Random::str(36, 50),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'metadata' => null,
                'cancellation_details' => null,
            ],
        ];

        for ($i = 0; $i < 20; $i++) {
            $payment = [
                'id' => Random::str(36, 50),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'metadata' => new Metadata(),
                'cancellation_details' => new PersonalDataCancellationDetails([
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ]),
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
                    'id' => '',
                    'type' => 'null',
                    'status' => '',
                    'created_at' => 'null',
                    'expires_at' => 'null',
                    'cancellation_details' => new stdClass(),
                    'metadata' => new stdClass(),
                ],
            ],
        ];
        for ($i = 0; $i < 10; $i++) {
            $personalData = [
                'id' => Random::str($i < 5 ? Random::int(1, 35) : Random::int(51, 64)),
                'type' => Random::str(10),
                'status' => Random::str(2, 35),
                'created_at' => 0 === $i ? '23423-234-32' : -Random::int(),
                'expires_at' => 0 === $i ? '23423-234-32' : -Random::int(),
                'cancellation_details' => 'null',
                'metadata' => 'null',
            ];
            $result[] = [$personalData];
        }

        return $result;
    }
}
