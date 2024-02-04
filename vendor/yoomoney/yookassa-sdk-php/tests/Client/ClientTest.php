<?php

namespace Tests\YooKassa\Client;

use Exception;
use InvalidArgumentException;
use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use ReflectionException;
use ReflectionMethod;
use YooKassa\Client;
use YooKassa\Client\ApiClientInterface;
use YooKassa\Client\CurlClient;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Common\LoggerWrapper;
use YooKassa\Helpers\Random;
use YooKassa\Helpers\StringObject;
use YooKassa\Model\Deal\DealType;
use YooKassa\Model\Deal\FeeMoment;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\PersonalData\PersonalDataType;
use YooKassa\Model\Receipt\ReceiptCustomer;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptType;
use YooKassa\Model\Receipt\Settlement;
use YooKassa\Request\Deals\CreateDealRequest;
use YooKassa\Request\Deals\CreateDealResponse;
use YooKassa\Request\Deals\DealResponse;
use YooKassa\Request\Deals\DealsRequest;
use YooKassa\Request\Deals\DealsResponse;
use YooKassa\Request\Payments\CancelResponse;
use YooKassa\Request\Payments\CreateCaptureRequest;
use YooKassa\Request\Payments\CreateCaptureResponse;
use YooKassa\Request\Payments\CreatePaymentRequest;
use YooKassa\Request\Payments\CreatePaymentResponse;
use YooKassa\Request\Payments\PaymentResponse;
use YooKassa\Request\Payments\PaymentsRequest;
use YooKassa\Request\Payments\PaymentsResponse;
use YooKassa\Request\Payouts\CreatePayoutRequest;
use YooKassa\Request\Payouts\CreatePayoutResponse;
use YooKassa\Request\Payouts\PayoutResponse;
use YooKassa\Request\Payouts\SbpBanksResponse;
use YooKassa\Request\PersonalData\CreatePersonalDataRequest;
use YooKassa\Request\PersonalData\PersonalDataResponse;
use YooKassa\Request\Receipts\AbstractReceiptResponse;
use YooKassa\Request\Receipts\CreatePostReceiptRequest;
use YooKassa\Request\Refunds\CreateRefundRequest;
use YooKassa\Request\Refunds\CreateRefundResponse;
use YooKassa\Request\Refunds\RefundResponse;
use YooKassa\Request\Refunds\RefundsRequest;
use YooKassa\Request\Refunds\RefundsResponse;
use YooKassa\Request\SelfEmployed\SelfEmployedRequest;
use YooKassa\Request\SelfEmployed\SelfEmployedResponse;

/**
 * @internal
 */
class ClientTest extends TestCase
{
    public function testCreatePayment(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setRecipient(['gateway_id' => 123, 'account_id' => 321])
            ->setAirline([])
            ->setDeal(null)
            ->setFraudData(null)
            ->setReceipt([])
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPaymentFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreatePaymentResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPaymentFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment([
                'amount' => [
                    'value' => 123,
                    'currency' => 'USD',
                ],
                'payment_token' => Random::str(36),
            ], 123)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreatePaymentResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPayment($payment, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPayment($payment, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws Exception
     */
    public function testInvalidCreatePayment($httpCode, $errorResponse, $requiredException): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setRecipient(['gateway_id' => 123, 'account_id' => 321])
            ->setAirline([])
            ->setDeal(null)
            ->setFraudData(null)
            ->setReceipt([])
            ->setPaymentToken(Random::str(36))
            ->build()
        ;
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Authorization' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->createPayment($payment, 123);
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider paymentsListDataProvider
     *
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testPaymentsList(mixed $request): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Authorization' => 'HeaderValue'],
                $this->getFixtures('getPaymentsFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPayments($request)
        ;

        $this->assertInstanceOf(PaymentsResponse::class, $response);
    }

    public static function paymentsListDataProvider(): array
    {
        return [
            [null],
            [PaymentsRequest::builder()->build()],
            [[
                'account_id' => 12,
            ]],
        ];
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     */
    public function testInvalidPaymentsList($httpCode, $errorResponse, $requiredException): void
    {
        $payments = PaymentsRequest::builder()->build();
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->getPayments($payments);
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider paymentInfoDataProvider
     *
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testGetPaymentInfo(mixed $paymentId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null !== $exceptionClassName ? self::never() : self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('paymentInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPaymentInfo($paymentId)
        ;

        self::assertInstanceOf(PaymentResponse::class, $response);
    }

    public static function paymentInfoDataProvider(): array
    {
        return [
            [Random::str(36)],
            [new StringObject(Random::str(36))],
            [true, InvalidArgumentException::class],
            [false, InvalidArgumentException::class],
            [0, InvalidArgumentException::class],
            [1, InvalidArgumentException::class],
            [0.1, InvalidArgumentException::class],
            [Random::str(35), InvalidArgumentException::class],
            [Random::str(37), InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws Exception
     */
    public function testInvalidGetPaymentInfo($httpCode, $errorResponse, $requiredException): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->getPaymentInfo(Random::str(36));
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    public function testCapturePayment(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('capturePaymentFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $capturePaymentRequest = [
            'amount' => [
                'value' => 123,
                'currency' => 'EUR',
            ],
        ];

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->capturePayment($capturePaymentRequest, '1ddd77af-0bd7-500d-895b-c475c55fdefc', 123)
        ;

        $this->assertInstanceOf(CreateCaptureResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('capturePaymentFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $capturePaymentRequest = CreateCaptureRequest::builder()->setAmount(10)->build();

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->capturePayment($capturePaymentRequest, '1ddd77af-0bd7-500d-895b-c475c55fdefc')
        ;

        $this->assertInstanceOf(CreateCaptureResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":123}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->capturePayment($capturePaymentRequest, '1ddd77af-0bd7-500d-895b-c475c55fdefc', 123)
            ;
            self::fail('Exception not thrown');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);
        }

        try {
            $apiClient->capturePayment($capturePaymentRequest, Random::str(37, 50), 123);
        } catch (InvalidArgumentException $e) {
            // it's ok
            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws Exception
     */
    public function testInvalidCapturePayment($httpCode, $errorResponse, $requiredException): void
    {
        $capturePaymentRequest = CreateCaptureRequest::builder()
            ->setAmount(10)
            ->setDeal(null)
            ->build();
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->capturePayment($capturePaymentRequest, '1ddd77af-0bd7-500d-895b-c475c55fdefc', 123);
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider paymentInfoDataProvider
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testPaymentIdCapturePayment(mixed $paymentId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null === $exceptionClassName ? self::once() : self::never())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('capturePaymentFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $capturePaymentRequest = [
            'amount' => [
                'value' => 123,
                'currency' => 'EUR',
            ],
        ];

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->capturePayment($capturePaymentRequest, $paymentId, 123)
        ;

        self::assertInstanceOf(CreateCaptureResponse::class, $response);
    }

    /**
     * @dataProvider paymentInfoDataProvider
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testCancelPayment(mixed $paymentId, string $exceptionClassName = null): void
    {
        $invalid = null !== $exceptionClassName;
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($invalid ? self::never() : self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('cancelPaymentFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        if ($invalid) {
            $this->expectException($exceptionClassName);
        }

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->cancelPayment($paymentId, 123)
        ;

        $this->assertInstanceOf(CancelResponse::class, $response);
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws ExtensionNotFoundException
     */
    public function testInvalidCancelPayment($httpCode, $errorResponse, $requiredException): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->cancelPayment(Random::str(36));
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider refundsDataProvider
     *
     * @param mixed $refundsRequest
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testGetRefunds($refundsRequest): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('refundsInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getRefunds($refundsRequest)
        ;

        $this->assertInstanceOf(RefundsResponse::class, $response);
    }

    public static function refundsDataProvider(): array
    {
        return [
            [null],
            [RefundsRequest::builder()->build()],
            [[
                'account_id' => 123,
            ]],
        ];
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws ExtensionNotFoundException
     */
    public function testInvalidGetRefunds($httpCode, $errorResponse, $requiredException): void
    {
        $refundsRequest = RefundsRequest::builder()->build();
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->getRefunds($refundsRequest);
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    public function testCreateRefund(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createRefundFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $refundRequest = CreateRefundRequest::builder()->setPaymentId('1ddd77af-0bd7-500d-895b-c475c55fdefc')->setAmount(123)->build();

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createRefund($refundRequest, 123)
        ;

        $this->assertInstanceOf(CreateRefundResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createRefundFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $refundRequest = [
            'payment_id' => '1ddd77af-0bd7-500d-895b-c475c55fdefc',
            'amount' => [
                'value' => 321,
                'currency' => 'RUB',
            ],
        ];

        $apiClient = new Client();

        $response = $apiClient
            ->setMaxRequestAttempts(2)
            ->setRetryTimeout(1000)
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createRefund($refundRequest)
        ;

        $this->assertInstanceOf(CreateRefundResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createRefund($refundRequest, 123)
            ;
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws Exception
     */
    public function testInvalidCreateRefund($httpCode, $errorResponse, $requiredException): void
    {
        $refundRequest = CreateRefundRequest::builder()->setPaymentId('1ddd77af-0bd7-500d-895b-c475c55fdefc')->setAmount(123)->build();
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->createRefund($refundRequest, 123);
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider paymentInfoDataProvider
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ExtensionNotFoundException
     */
    public function testRefundInfo(mixed $refundId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null === $exceptionClassName ? self::once() : self::never())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('refundInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getRefundInfo($refundId)
        ;

        $this->assertInstanceOf(RefundResponse::class, $response);

        try {
            $apiClient->getRefundInfo(Random::str(50, 100));
        } catch (InvalidArgumentException $e) {
            // it's ok
            return;
        }
        self::fail('Exception not thrown');
    }

    /**
     * @dataProvider errorResponseDataProvider
     *
     * @param mixed $httpCode
     * @param mixed $errorResponse
     * @param mixed $requiredException
     *
     * @throws Exception
     */
    public function testInvalidRefundInfo($httpCode, $errorResponse, $requiredException): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $errorResponse,
                ['http_code' => $httpCode],
            ])
        ;

        $apiClient = new Client();
        $apiClient->setApiClient($curlClientStub)->setAuth('123456', 'shopPassword');

        try {
            $apiClient->getRefundInfo(Random::str(36));
        } catch (ApiException $e) {
            self::assertInstanceOf($requiredException, $e);

            return;
        }
        self::fail('Exception not thrown');
    }

    public function testApiException(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                'unknown response here',
                ['http_code' => 444],
            ])
        ;
        $this->expectException(ApiException::class);

        $apiClient = new Client();
        $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment, 123)
        ;
    }

    public function testBadRequestException(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"description": "error_msg", "code": "error_code", "parameter_name": "parameter_name"}',
                ['http_code' => 400],
            ])
        ;
        $this->expectException(BadApiRequestException::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment, 123)
        ;
    }

    public function testTechnicalErrorException(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"description": "error_msg", "code": "error_code"}',
                ['http_code' => 500],
            ])
        ;
        $this->expectException(InternalServerError::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment, 123)
        ;
    }

    public function testUnauthorizedException(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"description": "error_msg"}',
                ['http_code' => 401],
            ])
        ;
        $this->expectException(UnauthorizedException::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment, 123)
        ;
    }

    public function testForbiddenException(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"description": "error_msg","error_code": "error_code", "parameter_name": "parameter_name", "operation_name": "operation_name"}',
                ['http_code' => 403],
            ])
        ;
        $this->expectException(ForbiddenException::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment, 123)
        ;
    }

    public function testNotFoundException(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"description": "error_msg","error_code": "error_code", "parameter_name": "parameter_name", "operation_name": "operation_name"}',
                ['http_code' => 404],
            ])
        ;
        $this->expectException(NotFoundException::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPaymentInfo(Random::str(36))
        ;
    }

    public function testToManyRequestsException(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"description": "error_msg","error_code": "error_code", "parameter_name": "parameter_name", "operation_name": "operation_name"}',
                ['http_code' => 429],
            ])
        ;
        $this->expectException(TooManyRequestsException::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPaymentInfo(Random::str(36))
        ;
    }

    public function testAnotherExceptions(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{}',
                ['http_code' => 322],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPaymentInfo(Random::str(36))
        ;

        self::assertNull($response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{}',
                ['http_code' => 402],
            ])
        ;

        $apiClient = new Client();

        $this->expectException(ApiException::class);

        $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPaymentInfo(Random::str(36))
        ;
    }

    public function testConfig(): void
    {
        $apiClient = new Client();
        $apiClient->setConfig([
            'url' => 'test',
        ]);

        $this->assertEquals(['url' => 'test'], $apiClient->getConfig());
    }

    public function testSetLogger(): void
    {
        $wrapped = new ArrayLogger();
        $logger = new LoggerWrapper($wrapped);

        $apiClient = new Client();
        $apiClient->setLogger($logger);

        $clientMock = $this->getMockBuilder(ApiClientInterface::class)
            ->onlyMethods(['setLogger', 'setConfig', 'call', 'setShopId', 'getUserAgent', 'setBearerToken', 'setShopPassword', 'setAdvancedCurlOptions'])
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $expectedLoggers = [];
        $clientMock->expects(self::exactly(3))->method('setLogger')->willReturnCallback(function ($logger) use (&$expectedLoggers): void {
            $expectedLoggers[] = $logger;
        });
        $clientMock->expects(self::once())->method('setConfig')->willReturn($clientMock);

        $apiClient->setApiClient($clientMock);
        self::assertSame($expectedLoggers[0], $logger);

        $apiClient->setLogger($wrapped);
        $apiClient->setLogger(function ($level, $log, $context = []) use ($wrapped): void {
            $wrapped->log($level, $log, $context);
        });
    }

    public function testDecodeInvalidData(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(self::any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"invalid":"json"',
                ['http_code' => 200],
            ])
        ;
        $this->expectException(JsonException::class);

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPaymentInfo(Random::str(36))
        ;
    }

    public function testEncodeMultibyteData(): void
    {
        $instance = new TestClient();

        $value = ['hello' => 'Привет', 'olleh' => 'سلام'];
        $result = $instance->encode($value);

        self::assertTrue(str_contains($result, 'Привет'));
        self::assertTrue(str_contains($result, 'سلام'));
    }

    /**
     * @dataProvider invalidJsonDataProvider
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function testEncodeInvalidData(mixed $value): void
    {
        $instance = new TestClient();

        $this->expectException(JsonException::class);
        $instance->encode($value);
    }

    public function invalidJsonDataProvider(): array
    {
        $recursion = ['test' => 'test', 'val' => null];
        $recursion['val'] = &$recursion;

        return [
            [
                $recursion
            ],
            [
                ['test' => iconv('utf-8', 'windows-1251', 'абвгдеёжз')]
            ],
        ];
    }

    public function testCreatePaymentErrors(): void
    {
        $payment = CreatePaymentRequest::builder()
            ->setAmount(123)
            ->setPaymentToken(Random::str(36))
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPaymentErrorsGeneralFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayment($payment)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreatePaymentResponse::class, $response);
        self::assertEquals('canceled', $response->getStatus());
        self::assertEquals('general_decline', $response->getCancellationDetails()->getReason());
    }

    /**
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ApiConnectionException
     * @throws AuthorizeException
     * @throws ExtensionNotFoundException
     */
    public function testCreateReceipt(): void
    {
        // Create Receipt via object
        $receipt = $this->createReceiptViaObject();
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createReceiptFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createReceipt($receipt)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(AbstractReceiptResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createReceiptFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        // Create Receipt via array
        $receipt = $this->createReceiptViaArray();
        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createReceipt($receipt, 123)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(AbstractReceiptResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createReceipt($receipt, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createReceipt($receipt, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    public function testCreateDeal(): void
    {
        $deal = CreateDealRequest::builder()
            ->setType(DealType::SAFE_DEAL)
            ->setFeeMoment(FeeMoment::PAYMENT_SUCCEEDED)
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createDealFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createDeal($deal)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreateDealResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createDealFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createDeal([
                'type' => 'safe_deal',
                'fee_moment' => 'payment_succeeded',
                'description' => Random::str(36),
            ], 123)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreateDealResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createDeal($deal, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createDeal($deal, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    /**
     * @dataProvider dealInfoDataProvider
     *
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ExtensionNotFoundException
     */
    public function testGetDealInfo(mixed $dealId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null !== $exceptionClassName ? self::never() : self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('dealInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getDealInfo($dealId)
        ;

        self::assertInstanceOf(DealResponse::class, $response);
    }

    public static function dealInfoDataProvider(): array
    {
        return [
            [Random::str(36)],
            [new StringObject(Random::str(36))],
            [true, InvalidArgumentException::class],
            [false, InvalidArgumentException::class],
            [0, InvalidArgumentException::class],
            [1, InvalidArgumentException::class],
            [0.1, InvalidArgumentException::class],
            [Random::str(35), InvalidArgumentException::class],
            [Random::str(51), InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider dealsDataProvider
     *
     * @param mixed $dealsRequest
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testGetDeals($dealsRequest): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('dealsInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getDeals($dealsRequest)
        ;

        $this->assertInstanceOf(DealsResponse::class, $response);
    }

    public static function dealsDataProvider(): array
    {
        return [
            [null],
            [DealsRequest::builder()->build()],
            [[
                'status' => 'closed',
            ]],
        ];
    }

    public function testCreatePayout(): void
    {
        $payout = CreatePayoutRequest::builder()
            ->setAmount(['value' => '320', 'currency' => 'RUB'])
            ->setPayoutDestinationData([
                'type' => PaymentMethodType::YOO_MONEY,
                'account_number' => '41001614575714',
            ])
            ->setDescription('Выплата по заказу №37')
            ->setMetadata(['order_id' => '37'])
            ->setDeal(['id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147'])
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPayoutFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayout($payout)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreatePayoutResponse::class, $response);

        $payout = CreatePayoutRequest::builder()
            ->setAmount(['value' => '320', 'currency' => 'RUB'])
            ->setPayoutToken('<Синоним банковской карты>')
            ->setDescription('Выплата по заказу №37')
            ->setMetadata(['order_id' => '37'])
            ->setDeal(['id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147'])
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPayoutFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayout($payout)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreatePayoutResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPayoutFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPayout([
                'amount' => ['value' => '320', 'currency' => 'RUB'],
                'description' => 'Выплата по заказу №37',
                'payout_token' => '<Синоним банковской карты>',
                'deal' => ['id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147'],
                'metadata' => ['order_id' => '37'],
            ], 123)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(CreatePayoutResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPayout($payout, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPayout($payout, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPayout([
                    'amount' => ['value' => '320', 'currency' => 'RUB'],
                    'description' => 'Выплата по заказу №37',
                    'payout_token' => '<Синоним банковской карты>',
                    'payout_destination_data' => ['type' => 'bank_card', 'card' => ['number' => '1234567890123456']],
                    'deal' => ['id' => 'dl-285e5ee7-0022-5000-8000-01516a44b147'],
                    'metadata' => ['order_id' => '37'],
                ], 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    /**
     * @dataProvider payoutInfoDataProvider
     *
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ExtensionNotFoundException
     */
    public function testGetPayoutInfo(mixed $payoutId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null !== $exceptionClassName ? self::never() : self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('payoutInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPayoutInfo($payoutId)
        ;

        self::assertInstanceOf(PayoutResponse::class, $response);
    }

    public static function payoutInfoDataProvider(): array
    {
        return [
            [Random::str(36)],
            [new StringObject(Random::str(36))],
            [true, InvalidArgumentException::class],
            [false, InvalidArgumentException::class],
            [0, InvalidArgumentException::class],
            [1, InvalidArgumentException::class],
            [0.1, InvalidArgumentException::class],
            [Random::str(35), InvalidArgumentException::class],
            [Random::str(51), InvalidArgumentException::class],
        ];
    }

    public function testCreatePersonalData(): void
    {
        $personalData = CreatePersonalDataRequest::builder()
            ->setType(PersonalDataType::SBP_PAYOUT_RECIPIENT)
            ->setLastName('Иванов')
            ->setFirstName('Иван')
            ->setMiddleName('Иванович')
            ->setMetadata(['recipient_id' => '37'])
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPersonalDataFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPersonalData($personalData)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(PersonalDataResponse::class, $response);

        $personalData = CreatePersonalDataRequest::builder()
            ->setType(PersonalDataType::SBP_PAYOUT_RECIPIENT)
            ->setLastName('Иванов')
            ->setFirstName('Иван')
            ->setMiddleName('Иванович')
            ->setMetadata(['recipient_id' => '37'])
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPersonalDataFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPersonalData($personalData)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(PersonalDataResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createPersonalDataFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createPersonalData([
                'type' => 'sbp_payout_recipient',
                'last_name' => 'Иванов',
                'first_name' => 'Иван',
                'middle_name' => 'Иванович',
                'metadata' => ['recipient_id' => '37'],
            ], 123)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(PersonalDataResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPersonalData($personalData, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPersonalData($personalData, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createPersonalData([
                    'type' => 'sbp_payout_recipient',
                    'last_name' => 'Иванов',
                    'first_name' => 'Иван',
                    'middle_name' => 'Иванович',
                    'metadata' => ['recipient_id' => '37'],
                ], 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    /**
     * @dataProvider personalDataInfoDataProvider
     *
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ExtensionNotFoundException
     */
    public function testGetPersonalDataInfo(mixed $personalDataId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null !== $exceptionClassName ? self::never() : self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('personalDataInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getPersonalDataInfo($personalDataId)
        ;

        self::assertInstanceOf(PersonalDataResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->getPersonalDataInfo($personalDataId)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    public static function personalDataInfoDataProvider(): array
    {
        return [
            [Random::str(36)],
            [new StringObject(Random::str(36))],
            [true, InvalidArgumentException::class],
            [false, InvalidArgumentException::class],
            [0, InvalidArgumentException::class],
            [1, InvalidArgumentException::class],
            [0.1, InvalidArgumentException::class],
            [Random::str(35), InvalidArgumentException::class],
            [Random::str(51), InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider paymentsListDataProvider
     *
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws ExtensionNotFoundException
     */
    public function testSbpBanksList(): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('getSbpBanksFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getSbpBanks()
        ;

        $this->assertInstanceOf(SbpBanksResponse::class, $response);
    }

    public function testCreateSelfEmployed(): void
    {
        $selfEmployed = SelfEmployedRequest::builder()
            ->setItn('123456789012')
            ->setPhone('79001002030')
            ->setConfirmation(['type' => 'redirect'])
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createSelfEmployedFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createSelfEmployed($selfEmployed)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(SelfEmployedResponse::class, $response);

        $selfEmployed = SelfEmployedRequest::builder()
            ->setItn('123456789012')
            ->setPhone('79001002030')
            ->setConfirmation(['type' => 'redirect'])
            ->build()
        ;

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createSelfEmployedFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createSelfEmployed($selfEmployed)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(SelfEmployedResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('createSelfEmployedFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();
        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->createSelfEmployed([
                'itn' => '123456789012',
                'phone' => '79001002030',
                'confirmation' => ['type' => 'redirect'],
            ], 123)
        ;

        self::assertSame($curlClientStub, $apiClient->getApiClient());
        self::assertInstanceOf(SelfEmployedResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createSelfEmployed($selfEmployed, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ApiException $e) {
            self::assertInstanceOf(ResponseProcessingException::class, $e);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createSelfEmployed($selfEmployed, 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted"}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->createSelfEmployed([
                    'itn' => '123456789012',
                    'phone' => '79001002030',
                    'confirmation' => ['type' => 'redirect'],
                ], 123)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    /**
     * @dataProvider selfEmployedInfoDataProvider
     *
     * @param mixed $selfEmployedId
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function testGetSelfEmployedInfo($selfEmployedId, string $exceptionClassName = null): void
    {
        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects(null !== $exceptionClassName ? self::never() : self::once())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                $this->getFixtures('selfEmployedInfoFixtures.json'),
                ['http_code' => 200],
            ])
        ;

        $apiClient = new Client();

        if (null !== $exceptionClassName) {
            $this->expectException($exceptionClassName);
        }

        $response = $apiClient
            ->setApiClient($curlClientStub)
            ->setAuth('123456', 'shopPassword')
            ->getSelfEmployedInfo($selfEmployedId)
        ;

        self::assertInstanceOf(SelfEmployedResponse::class, $response);

        $curlClientStub = $this->getCurlClientStub();
        $curlClientStub
            ->expects($this->any())
            ->method('sendRequest')
            ->willReturn([
                ['Header-Name' => 'HeaderValue'],
                '{"type":"error","code":"request_accepted","retry_after":1800}',
                ['http_code' => 202],
            ])
        ;

        try {
            $apiClient->setRetryTimeout(0);
            $response = $apiClient
                ->setApiClient($curlClientStub)
                ->setAuth('123456', 'shopPassword')
                ->getSelfEmployedInfo($selfEmployedId)
            ;
            self::fail('Исключение не было выброшено');
        } catch (ResponseProcessingException $e) {
            self::assertEquals(Client::DEFAULT_DELAY, $e->retryAfter);

            return;
        }
    }

    public static function selfEmployedInfoDataProvider(): array
    {
        return [
            [Random::str(36)],
            [new StringObject(Random::str(36))],
            [true, InvalidArgumentException::class],
            [false, InvalidArgumentException::class],
            [0, InvalidArgumentException::class],
            [1, InvalidArgumentException::class],
            [0.1, InvalidArgumentException::class],
            [Random::str(35), InvalidArgumentException::class],
            [Random::str(51), InvalidArgumentException::class],
        ];
    }

    public function getCurlClientStub(): MockObject
    {
        return $this->getMockBuilder(CurlClient::class)
            ->onlyMethods(['sendRequest'])
            ->getMock()
        ;
    }

    public static function errorResponseDataProvider(): array
    {
        return [
            [NotFoundException::HTTP_CODE, '{}', NotFoundException::class],
            [BadApiRequestException::HTTP_CODE, '{}', BadApiRequestException::class],
            [BadApiRequestException::HTTP_CODE, '{}', BadApiRequestException::class],
            [ForbiddenException::HTTP_CODE, '{}', ForbiddenException::class],
            [UnauthorizedException::HTTP_CODE, '{}', UnauthorizedException::class],
            [TooManyRequestsException::HTTP_CODE, '{}', TooManyRequestsException::class],
        ];
    }

    private function createReceiptViaArray(): array
    {
        return [
            'customer' => [
                'full_name' => 'Иванов Иван Иванович',
                'inn' => '6321341814',
                'email' => 'johndoe@yoomoney.ru',
                'phone' => '79000000000',
            ],
            'items' => [
                [
                    'description' => 'string',
                    'quantity' => 1,
                    'amount' => [
                        'value' => '10.00',
                        'currency' => 'RUB',
                    ],
                    'vat_code' => 1,
                    'payment_subject' => 'commodity',
                    'payment_mode' => 'full_prepayment',
                    'product_code' => '00 00 00 01 00 21 FA 41 00 23 05 41 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 12 00 AB 00',
                    'country_of_origin_code' => 'RU',
                    'customs_declaration_number' => '10714040/140917/0090376',
                    'excise' => '20.00',
                ],
            ],
            'tax_system_code' => 1,
            'type' => 'payment',
            'send' => true,
            'settlements' => [
                [
                    'type' => 'cashless',
                    'amount' => [
                        'value' => '10.00',
                        'currency' => 'RUB',
                    ],
                ],
            ],
            'payment_id' => '1da5c87d-0984-50e8-a7f3-8de646dd9ec9',
        ];
    }

    private function createReceiptViaObject(): CreatePostReceiptRequest
    {
        $customer = new ReceiptCustomer([
            'full_name' => 'Иванов Иван Иванович',
            'inn' => '6321341814',
            'email' => 'johndoe@yoomoney.ru',
            'phone' => '79000000000',
        ]);
        $settlement = new Settlement([
            'type' => 'cashless',
            'amount' => [
                'value' => '10.00',
                'currency' => 'RUB',
            ],
        ]);
        $receiptItem = new ReceiptItem([
            'description' => 'string',
            'quantity' => 1,
            'amount' => [
                'value' => '10.00',
                'currency' => 'RUB',
            ],
            'vat_code' => 1,
            'payment_subject' => 'commodity',
            'payment_mode' => 'full_prepayment',
            'product_code' => '00 00 00 01 00 21 FA 41 00 23 05 41 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 00 12 00 AB 00',
            'country_of_origin_code' => 'RU',
            'customs_declaration_number' => '10714040/140917/0090376',
            'excise' => '20.00',
        ]);

        return CreatePostReceiptRequest::builder()
            ->setCustomer($customer)
            ->setType(ReceiptType::PAYMENT)
            ->setObjectId('1da5c87d-0984-50e8-a7f3-8de646dd9ec9', ReceiptType::PAYMENT)
            ->setSend(true)
            ->setSettlements([$settlement])
            ->setOnBehalfOf('545665')
            ->setItems([$receiptItem])
            ->build()
        ;
    }

    private function getFixtures($fileName): bool|string
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $fileName);
    }
}

class ArrayLogger implements LoggerInterface
{
    private array $lastLog;

    public function log($level, $message, array $context = []): void
    {
        $this->lastLog = [$level, $message, $context];
    }

    public function getLastLog(): array
    {
        return $this->lastLog;
    }

    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}

class TestClient extends Client
{
    /**
     * @param mixed $data
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function encode(mixed $data): mixed
    {
        $reflection = new ReflectionMethod($this, 'encodeData');
        $reflection->setAccessible(true);

        return $reflection->invoke($this, $data);
    }
}
