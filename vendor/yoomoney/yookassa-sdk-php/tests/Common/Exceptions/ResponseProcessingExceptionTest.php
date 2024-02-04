<?php

namespace Tests\YooKassa\Common\Exceptions;

use YooKassa\Common\Exceptions\ResponseProcessingException;

/**
 * @internal
 */
class ResponseProcessingExceptionTest extends ApiExceptionTest
{
    public function getTestInstance($message = '', $code = 0, $responseHeaders = [], $responseBody = '')
    {
        return new ResponseProcessingException($responseHeaders, $responseBody);
    }

    public function expectedHttpCode()
    {
        return ResponseProcessingException::HTTP_CODE;
    }

    /**
     * @dataProvider descriptionDataProvider
     */
    public function testDescription(string $body): void
    {
        $instance = $this->getTestInstance('', 0, [], $body);
        $tmp = json_decode($body, true);
        if (empty($tmp['description'])) {
            self::assertEquals('', $instance->getMessage());
        } else {
            self::assertEquals($tmp['description'] . '.', $instance->getMessage());
        }
    }

    public static function descriptionDataProvider()
    {
        return [
            ['{}'],
            ['{"description":"test"}'],
            ['{"description":"У попа была собака"}'],
        ];
    }

    /**
     * @dataProvider retryAfterDataProvider
     */
    public function testRetryAfter(string $body): void
    {
        $instance = $this->getTestInstance('', 0, [], $body);
        $tmp = json_decode($body, true);
        if (empty($tmp['retry_after'])) {
            self::assertNull($instance->retryAfter);
        } else {
            self::assertEquals($tmp['retry_after'], $instance->retryAfter);
        }
    }

    public static function retryAfterDataProvider()
    {
        return [
            ['{}'],
            ['{"retry_after":-20}'],
            ['{"retry_after":123}'],
        ];
    }

    /**
     * @dataProvider typeDataProvider
     */
    public function testType(string $body): void
    {
        $instance = $this->getTestInstance('', 0, [], $body);
        $tmp = json_decode($body, true);
        if (empty($tmp['type'])) {
            self::assertNull($instance->type);
        } else {
            self::assertEquals($tmp['type'], $instance->type);
        }
    }

    public static function typeDataProvider()
    {
        return [
            ['{}'],
            ['{"type":"server_error"}'],
            ['{"type":"error"}'],
        ];
    }

    /**
     * @dataProvider messageDataProvider
     *
     * @param mixed $body
     */
    public function testMessage($body): void
    {
        $instance = $this->getTestInstance('', 0, [], $body);

        $tmp = json_decode($body, true);
        $message = '';
        if (!empty($tmp['description'])) {
            $message = $tmp['description'] . '.';
        }
        self::assertEquals($message, $instance->getMessage());

        if (empty($tmp['retry_after'])) {
            self::assertNull($instance->retryAfter);
        } else {
            self::assertEquals($tmp['retry_after'], $instance->retryAfter);
        }
        if (empty($tmp['type'])) {
            self::assertNull($instance->type);
        } else {
            self::assertEquals($tmp['type'], $instance->type);
        }
    }

    public static function messageDataProvider()
    {
        return [
            ['{}'],
            ['{"code":"server_error","description":"Internal server error"}'],
            ['{"code":"server_error","description":"Invalid parameter value","parameter":"shop_id"}'],
            ['{"code":"server_error","description":"Invalid parameter value","parameter":"shop_id","type":"test"}'],
            ['{"code":"server_error","description":"Invalid parameter value","parameter":"shop_id","retry_after":333}'],
        ];
    }

    public function testExceptionCode(): void
    {
        $instance = $this->getTestInstance();
        self::assertEquals($this->expectedHttpCode(), $instance->getCode());
    }
}
