<?php

namespace HTMLValidator\Tests;

use HTMLValidator\Exception;
use HTMLValidator\HTMLValidator;
use PHPUnit\Framework\TestCase;

class HTMLValidatorTest extends TestCase
{
    public function testValidateUriValid(): void
    {
        $html = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>test</title></head><body></body></html>';
        $result = (new HTMLValidator())->validateUri($this->dataUri($html));

        self::assertTrue($result->isValid());
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertSame('text/html', $result->getType());
        self::assertStringContainsString('test', $result->getSource());
    }

    public function testValidateUriInvalid(): void
    {
        $html = '<html lang="en"><head><meta charset="utf-8"></head><body>test</body></html>';
        $result = (new HTMLValidator())->validateUri($this->dataUri($html));

        self::assertFalse($result->isValid());
        self::assertCount(2, $result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertSame('text/html', $result->getType());
        self::assertStringContainsString('test', $result->getSource());

        $error1 = $result->getErrors()[0];

        self::assertSame(1, $error1->getLastLine());
        self::assertNull($error1->getFirstLine());
        self::assertSame(16, $error1->getLastColumn());
        self::assertSame(1, $error1->getFirstColumn());
        self::assertSame('Start tag seen without seeing a doctype first. Expected “<!DOCTYPE html>”.', $error1->getMessage());
        self::assertSame('<html lang="en"><head>', $error1->getExtract());
        self::assertSame(0, $error1->getHiliteStart());
        self::assertSame(16, $error1->getHiliteLength());

        $error2 = $result->getErrors()[1];

        self::assertSame(1, $error2->getLastLine());
        self::assertNull($error2->getFirstLine());
        self::assertSame(51, $error2->getLastColumn());
        self::assertSame(45, $error2->getFirstColumn());
        self::assertSame('Element “head” is missing a required instance of child element “title”.', $error2->getMessage());
        self::assertSame('t="utf-8"></head><body>', $error2->getExtract());
        self::assertSame(10, $error2->getHiliteStart());
        self::assertSame(7, $error2->getHiliteLength());
    }

    public function testValidateUriNonDocumentError(): void
    {
        $this->expectException(Exception::class);

        (new HTMLValidator())->validateUri('http://nonexistent-domain-xyz-12345.invalid');
    }

    public function testValidHTMLFile(): void
    {
        $result = (new HTMLValidator())->validateFile(__DIR__.'/fixtures/valid.html');

        self::assertTrue($result->isValid());
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertNull($result->getUri());
        self::assertSame('text/html', $result->getType());
        self::assertSame('UTF-8', $result->getEncoding());
        self::assertStringContainsString('test', $result->getSource());
    }

    public function testValidateFileMissing(): void
    {
        $this->expectException(Exception::class);

        (new HTMLValidator())->validateFile(__DIR__.'/fixtures/does-not-exist.html');
    }

    public function testValidHTMLFragment(): void
    {
        $html = '<!DOCTYPE html><html lang="en"><head><title>test</title></head> <body></body></html>';
        $result = (new HTMLValidator())->validateFragment($html);

        self::assertTrue($result->isValid());
        self::assertEmpty($result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertNull($result->getUri());
        self::assertSame('text/html', $result->getType());
        self::assertSame('UTF-8', $result->getEncoding());
        self::assertStringContainsString('test', $result->getSource());
    }

    public function testErrorHTMLFragment(): void
    {
        $html = '<html lang="en"><body>test</body></html>';
        $result = (new HTMLValidator())->validateFragment($html);

        self::assertFalse($result->isValid());
        self::assertCount(2, $result->getErrors());
        self::assertEmpty($result->getWarnings());
        self::assertNull($result->getUri());
        self::assertSame('text/html', $result->getType());
        self::assertSame('UTF-8', $result->getEncoding());
        self::assertStringContainsString('test', $result->getSource());

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
        self::assertSame('lang="en"><body>test</', $error2->getExtract());
        self::assertSame(10, $error2->getHiliteStart());
        self::assertSame(6, $error2->getHiliteLength());
        self::assertNull($error2->getUri());
    }

    public function testWarningHTMLFragment(): void
    {
        $html = '<!DOCTYPE html><html><head><title>test</title></head> <body></body></html>';
        $result = (new HTMLValidator())->validateFragment($html);

        self::assertTrue($result->isValid());
        self::assertEmpty($result->getErrors());
        self::assertCount(1, $result->getWarnings());
        self::assertNull($result->getUri());
        self::assertSame('text/html', $result->getType());
        self::assertSame('UTF-8', $result->getEncoding());
        self::assertStringContainsString('test', $result->getSource());

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

    private function dataUri(string $html): string
    {
        return 'data:text/html,'.\rawurlencode($html);
    }
}
