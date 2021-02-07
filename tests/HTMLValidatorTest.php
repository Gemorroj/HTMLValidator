<?php

namespace HTMLValidator\Tests;

use HTMLValidator\HTMLValidator;
use PHPUnit\Framework\TestCase;

class HTMLValidatorTest extends TestCase
{
    public function testUri(): void
    {
        $validator = new HTMLValidator();
        $result = $validator->validateUri('http://example.com');
        self::assertNotEmpty($result->getErrors());
        self::assertNotEmpty($result->getWarnings());

        self::assertFalse($result->isValid());
        self::assertNull($result->getType());
        self::assertNull($result->getEncoding());
    }

    public function testValidHTMLFile(): void
    {
        $validator = new HTMLValidator();
        $result = $validator->validateFile(__DIR__.'/fixtures/valid.html');
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());

        self::assertTrue($result->isValid());
        self::assertNull($result->getType());
        self::assertNull($result->getEncoding());
    }

    public function testValidHTMLFragment(): void
    {
        $html = '<!DOCTYPE html><html lang="en"><head><title>test</title></head> <body></body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($html);
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());

        self::assertTrue($result->isValid());
        self::assertNull($result->getType());
        self::assertNull($result->getEncoding());
    }

    public function testErrorHTMLFragment(): void
    {
        $html = '<html lang="en"><body> </body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($html);
        self::assertCount(2, $result->getErrors());
        self::assertEmpty($result->getWarnings());

        self::assertFalse($result->isValid());
        self::assertNull($result->getType());
        self::assertNull($result->getEncoding());

        $error1 = $result->getErrors()[0];

        self::assertSame(1, $error1->getLastLine());
        self::assertNull($error1->getFirstLine());
        self::assertSame(16, $error1->getLastColumn());
        self::assertSame(1, $error1->getFirstColumn());
        self::assertSame('Start tag seen without seeing a doctype first. Expected “<!DOCTYPE html>”.', $error1->getMessage());
        self::assertSame('<html lang="en"><body>', $error1->getExtract());
        self::assertSame(0, $error1->getHiliteStart());
        self::assertSame(16, $error1->getHiliteLength());
        self::assertNull($error1->getUri());

        $error2 = $result->getErrors()[1];

        self::assertSame(1, $error2->getLastLine());
        self::assertNull($error2->getFirstLine());
        self::assertSame(22, $error2->getLastColumn());
        self::assertSame(17, $error2->getFirstColumn());
        self::assertSame('Element “head” is missing a required instance of child element “title”.', $error2->getMessage());
        self::assertSame('lang="en"><body> </bod', $error2->getExtract());
        self::assertSame(10, $error2->getHiliteStart());
        self::assertSame(6, $error2->getHiliteLength());
        self::assertNull($error2->getUri());
    }

    public function testWarningHTMLFragment(): void
    {
        $html = '<!DOCTYPE html><html><head><title>test</title></head> <body></body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($html);
        self::assertEmpty($result->getErrors());
        self::assertCount(1, $result->getWarnings());

        self::assertTrue($result->isValid());
        self::assertNull($result->getType());
        self::assertNull($result->getEncoding());

        $warning = $result->getWarnings()[0];

        self::assertSame(1, $warning->getLastLine());
        self::assertNull($warning->getFirstLine());
        self::assertSame(21, $warning->getLastColumn());
        self::assertSame(16, $warning->getFirstColumn());
        self::assertSame('Consider adding a “lang” attribute to the “html” start tag to declare the language of this document.', $warning->getMessage());
        self::assertSame('TYPE html><html><head>', $warning->getExtract());
        self::assertSame(10, $warning->getHiliteStart());
        self::assertSame(6, $warning->getHiliteLength());
        self::assertNull($warning->getUri());
    }
}
