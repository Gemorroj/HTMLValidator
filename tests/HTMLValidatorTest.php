<?php

namespace HTMLValidator\Tests;

use HTMLValidator\HTMLValidator;
use PHPUnit\Framework\TestCase;

class HTMLValidatorTest extends TestCase
{
    public function testValidHTML5Fragment(): void
    {
        $html = '<!DOCTYPE html><html><head><title>test</title></head> <body></body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($html);
        self::assertEmpty($result->getErrors());
    }

    public function testInvalidHTML5Fragment(): void
    {
        $html = '<html><body> <test> </body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($html);
        self::assertNotEmpty($result->getErrors());
    }
}
