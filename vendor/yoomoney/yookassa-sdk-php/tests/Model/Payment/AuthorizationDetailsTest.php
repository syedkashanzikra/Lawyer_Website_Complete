<?php

namespace Tests\YooKassa\Model\Payment;

use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;
use YooKassa\Helpers\Random;
use YooKassa\Model\Payment\AuthorizationDetails;
use YooKassa\Model\Payment\ThreeDSecure;
use YooKassa\Validator\Exceptions\ValidatorParameterException;

/**
 * @internal
 */
class AuthorizationDetailsTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testConstructor(array $authorizationDetails): void
    {
        $instance = self::getInstance($authorizationDetails);

        self::assertEquals($authorizationDetails['rrn'], $instance->getRrn());
        self::assertEquals($authorizationDetails['auth_code'], $instance->getAuthCode());
        self::assertInstanceOf(ThreeDSecure::class, $instance->getThreeDSecure());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetRrn(array $authorizationDetails): void
    {
        $instance = self::getInstance($authorizationDetails);
        self::assertEquals($authorizationDetails['rrn'], $instance->getRrn());

        $instance = self::getInstance($authorizationDetails);
        $instance->setRrn($authorizationDetails['rrn']);
        self::assertEquals($authorizationDetails['rrn'], $instance->getRrn());
        self::assertEquals($authorizationDetails['rrn'], $instance->rrn);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetAuthCode(array $authorizationDetails): void
    {
        $instance = self::getInstance($authorizationDetails);
        self::assertEquals($authorizationDetails['auth_code'], $instance->getAuthCode());

        $instance = self::getInstance($authorizationDetails);
        $instance->setAuthCode($authorizationDetails['auth_code']);
        self::assertEquals($authorizationDetails['auth_code'], $instance->getAuthCode());
        self::assertEquals($authorizationDetails['auth_code'], $instance->authCode);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testGetSetThreeDSecure(array $authorizationDetails): void
    {
        $instance = self::getInstance($authorizationDetails);
        self::assertInstanceOf(ThreeDSecure::class, $instance->getThreeDSecure());

        $instance = self::getInstance($authorizationDetails);
        $instance->setThreeDSecure($authorizationDetails['three_d_secure']);

        if (is_object($authorizationDetails['three_d_secure'])) {
            $threeDSecureObj = $authorizationDetails['three_d_secure'];
            $threeDSecureExpect = $threeDSecureObj->getApplied();
        } else {
            $threeDSecureExpect = $authorizationDetails['three_d_secure']['applied'];
        }

        self::assertInstanceOf(ThreeDSecure::class, $instance->getThreeDSecure());
        self::assertInstanceOf(ThreeDSecure::class, $instance->threeDSecure);

        self::assertEquals($threeDSecureExpect, $instance->getThreeDSecure()->getApplied());
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetInvalidRrn($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->setRrn($value);
    }

    /**
     * @dataProvider invalidValueDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidAuthCode($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->setAuthCode($value);
    }

    /**
     * @dataProvider invalidThreeDSecureDataProvider
     *
     * @param mixed $value
     */
    public function testSetterInvalidThreeDSecure($value, string $exceptionClassName): void
    {
        $instance = self::getInstance();
        $this->expectException($exceptionClassName);
        $instance->setThreeDSecure($value);
    }

    /**
     * @throws Exception
     */
    public static function validDataProvider(): array
    {
        return [
            [
                'authorizationDetails' => [
                    'rrn' => null,
                    'auth_code' => null,
                    'three_d_secure' => [
                        'applied' => false,
                    ],
                ],
            ],
            [
                'authorizationDetails' => [
                    'rrn' => Random::str(32),
                    'auth_code' => Random::str(32),
                    'three_d_secure' => [
                        'applied' => true,
                    ],
                ],
            ],
            [
                'authorizationDetails' => [
                    'rrn' => Random::str(32),
                    'auth_code' => Random::str(32),
                    'three_d_secure' => new ThreeDSecure([
                        'applied' => true,
                    ]),
                ],
            ],
        ];
    }

    public static function invalidValueDataProvider()
    {
        return [
            [[-1], TypeError::class],
            [new stdClass(), TypeError::class],
            [new DateTime(), TypeError::class],
        ];
    }

    public static function invalidThreeDSecureDataProvider()
    {
        return [
            [-1, ValidatorParameterException::class],
            [-0.01, ValidatorParameterException::class],
            [0.0, ValidatorParameterException::class],
            [true, ValidatorParameterException::class],
            [false, ValidatorParameterException::class],
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testJsonSerialize(array $authorizationDetails): void
    {
        $instance = new AuthorizationDetails($authorizationDetails);

        $expected = [
            'three_d_secure' => is_object($authorizationDetails['three_d_secure'])
                    ? $authorizationDetails['three_d_secure']->jsonSerialize()
                    : $authorizationDetails['three_d_secure'],
        ];
        if (!empty($authorizationDetails['rrn'])) {
            $expected['rrn'] = $authorizationDetails['rrn'];
        }
        if (!empty($authorizationDetails['auth_code'])) {
            $expected['auth_code'] = $authorizationDetails['auth_code'];
        }
        self::assertEquals($expected, $instance->jsonSerialize());
    }

    protected static function getInstance(array $authorizationDetails = ['three_d_secure' => ['applied' => false]]): AuthorizationDetails
    {
        return new AuthorizationDetails($authorizationDetails);
    }
}
