<?php

namespace HTMLValidator\Tests;

use HTMLValidator\Message\Error;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testMapsJsonNode(): void
    {
        $message = new Error([
            'type' => 'error',
            'url' => 'https://example.com',
            'message' => 'boom',
            'extract' => 'abc',
            'hiliteStart' => 5,
            'hiliteLength' => 6,
            'firstLine' => 1,
            'lastLine' => 2,
            'firstColumn' => 3,
            'lastColumn' => 4,
        ]);

        self::assertSame('https://example.com', $message->getUri());
        self::assertSame('boom', $message->getMessage());
        self::assertSame('abc', $message->getExtract());
        self::assertSame(5, $message->getHiliteStart());
        self::assertSame(6, $message->getHiliteLength());
        self::assertSame(1, $message->getFirstLine());
        self::assertSame(2, $message->getLastLine());
        self::assertSame(3, $message->getFirstColumn());
        self::assertSame(4, $message->getLastColumn());
    }

    public function testMissingFieldsAreNull(): void
    {
        $message = new Error([]);

        self::assertNull($message->getUri());
        self::assertNull($message->getMessage());
        self::assertNull($message->getExtract());
        self::assertNull($message->getHiliteStart());
        self::assertNull($message->getHiliteLength());
        self::assertNull($message->getFirstLine());
        self::assertNull($message->getLastLine());
        self::assertNull($message->getFirstColumn());
        self::assertNull($message->getLastColumn());
    }

    public function testSetters(): void
    {
        $message = (new Error([]))
            ->setMessage('boom')
            ->setUri('https://example.com')
            ->setFirstLine(1)
            ->setHiliteStart(2);

        self::assertSame('boom', $message->getMessage());
        self::assertSame('https://example.com', $message->getUri());
        self::assertSame(1, $message->getFirstLine());
        self::assertSame(2, $message->getHiliteStart());
    }
}
