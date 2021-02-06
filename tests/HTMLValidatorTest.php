<?php

namespace HTMLValidator\Tests;

use HTMLValidator\HTMLValidator;
use PHPUnit\Framework\TestCase;

class HTMLValidatorTest extends TestCase
{
    public function testValidHTML(): void
    {
        $css = '<html><body></body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($css);
        self::assertEmpty($result->getErrors());
    }

    public function testInvalidHTML(): void
    {
        $css = '<html><body> <test> </body></html>';
        $validator = new HTMLValidator();
        $result = $validator->validateFragment($css);
        self::assertNotEmpty($result->getErrors());
    }
}
