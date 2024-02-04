<?php

namespace Tests\YooKassa\Common\Exceptions;

use YooKassa\Common\Exceptions\ForbiddenException;

/**
 * @internal
 */
class ForbiddenExceptionTest extends AbstractTestApiRequestException
{
    public function getTestInstance($message = '', $code = 0, $responseHeaders = [], $responseBody = '')
    {
        return new ForbiddenException($responseHeaders, $responseBody);
    }

    public function expectedHttpCode()
    {
        return ForbiddenException::HTTP_CODE;
    }
}
