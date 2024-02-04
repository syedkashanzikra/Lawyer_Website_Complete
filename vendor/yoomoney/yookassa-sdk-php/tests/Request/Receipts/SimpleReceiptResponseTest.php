<?php

namespace Tests\YooKassa\Request\Receipts;

use YooKassa\Request\Receipts\SimpleReceiptResponse;

/**
 * @internal
 */
class SimpleReceiptResponseTest extends AbstractTestReceiptResponse
{
    protected string $type = 'simple';

    /**
     * @dataProvider validDataProvider
     */
    public function testSpecificProperties(array $options): void
    {
        $instance = $this->getTestInstance($options);
        self::assertEquals($options['id'], $instance->getId());
    }

    protected function getTestInstance($options): SimpleReceiptResponse
    {
        return new SimpleReceiptResponse($options);
    }

    protected function addSpecificProperties($options, $i): array
    {
        return $options;
    }
}
