<?php

namespace Tests\YooKassa\Request\PersonalData;

use PHPUnit\Framework\TestCase;
use YooKassa\Helpers\Random;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsPartyCode;
use YooKassa\Model\PersonalData\PersonalDataCancellationDetailsReasonCode;
use YooKassa\Model\PersonalData\PersonalDataStatus;
use YooKassa\Model\PersonalData\PersonalDataType;
use YooKassa\Request\PersonalData\PersonalDataResponse;

/**
 * @internal
 */
class PersonalDataResponseTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testGetId(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetStatus(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        self::assertEquals($options['status'], $instance->getStatus());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetType(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        self::assertEquals($options['type'], $instance->getType());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetCreatedAt(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['created_at'])) {
            self::assertNull($instance->getCreatedAt());
            self::assertNull($instance->created_at);
            self::assertNull($instance->createdAt);
        } else {
            self::assertEquals($options['created_at'], $instance->getCreatedAt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetExpiresAt(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['expires_at'])) {
            self::assertNull($instance->getExpiresAt());
            self::assertNull($instance->expires_at);
            self::assertNull($instance->expiresAt);
        } else {
            self::assertEquals($options['expires_at'], $instance->getExpiresAt()->format(YOOKASSA_DATE));
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetCancellationDetails(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['cancellation_details'])) {
            self::assertNull($instance->getCancellationDetails());
        } else {
            self::assertEquals(
                $options['cancellation_details']['party'],
                $instance->getCancellationDetails()->getParty()
            );
            self::assertEquals(
                $options['cancellation_details']['reason'],
                $instance->getCancellationDetails()->getReason()
            );
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetMetadata(array $options): void
    {
        $instance = $this->getTypeInstance($options);
        if (empty($options['metadata'])) {
            self::assertNull($instance->getMetadata());
        } else {
            self::assertEquals($options['metadata'], $instance->getMetadata()->toArray());
        }
    }

    public static function validDataProvider()
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
                'expires_at' => date(YOOKASSA_DATE, Random::int(111111111, time())),
                'metadata' => ['order_id' => '37'],
                'cancellation_details' => [
                    'party' => Random::value($cancellationDetailsParties),
                    'reason' => Random::value($cancellationDetailsReasons),
                ],
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
            $data = [
                'id' => Random::str(36, 50),
                'status' => Random::value(PersonalDataStatus::getValidValues()),
                'type' => Random::value(PersonalDataType::getValidValues()),
                'created_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'expires_at' => date(YOOKASSA_DATE, Random::int(1, time())),
                'metadata' => [Random::str(3, 128, 'abcdefghijklmnopqrstuvwxyz') => Random::str(1, 512)],
                'cancellation_details' => [
                    'party' => $cancellationDetailsParties[$i % $countCancellationDetailsParties],
                    'reason' => $cancellationDetailsReasons[$i % $countCancellationDetailsReasons],
                ],
            ];
            $result[] = [$data];
        }

        return $result;
    }

    /**
     * @param mixed $options
     */
    private function getTypeInstance($options): PersonalDataResponse
    {
        return new PersonalDataResponse($options);
    }
}
