<?php

namespace HTMLValidator\Tests;

use HTMLValidator\Message\Error;
use HTMLValidator\Message\Warning;
use HTMLValidator\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testDefaults(): void
    {
        $response = new Response();

        self::assertNull($response->getEncoding());
        self::assertNull($response->getSource());
        self::assertNull($response->getType());
        self::assertNull($response->getUri());
        self::assertFalse($response->isValid());
        self::assertSame([], $response->getErrors());
        self::assertSame([], $response->getWarnings());
    }

    public function testSetters(): void
    {
        $error = new Error(['type' => 'error', 'message' => 'boom']);
        $warning = new Warning(['type' => 'info', 'message' => 'warn']);

        $response = (new Response())
            ->setEncoding('UTF-8')
            ->setSource('<html></html>')
            ->setType('text/html')
            ->setUri('https://example.com')
            ->setValid(true)
            ->setErrors([$error])
            ->setWarnings([$warning]);

        self::assertSame('UTF-8', $response->getEncoding());
        self::assertSame('<html></html>', $response->getSource());
        self::assertSame('text/html', $response->getType());
        self::assertSame('https://example.com', $response->getUri());
        self::assertTrue($response->isValid());
        self::assertSame([$error], $response->getErrors());
        self::assertSame([$warning], $response->getWarnings());
    }

    public function testAddErrorAndWarning(): void
    {
        $error = new Error(['message' => 'boom']);
        $warning = new Warning(['message' => 'warn']);

        $response = (new Response())
            ->addError($error)
            ->addWarning($warning);

        self::assertSame([$error], $response->getErrors());
        self::assertSame([$warning], $response->getWarnings());
    }
}
