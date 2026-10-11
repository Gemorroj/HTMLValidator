<?php

namespace HTMLValidator\Tests;

use HTMLValidator\Options\Options;
use HTMLValidator\Options\ParserType;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testDefaultParser(): void
    {
        $options = new Options();

        self::assertSame('html5', $options->getParser());
        self::assertSame(['parser' => 'html5'], $options->buildOptions());
    }

    public function testSetParserWithEnum(): void
    {
        $options = new Options();
        $options->setParser(ParserType::Xml);

        self::assertSame('xml', $options->getParser());
        self::assertSame(['parser' => 'xml'], $options->buildOptions());
    }

    public function testSetParserWithString(): void
    {
        $options = new Options();
        $options->setParser('xmldtd');

        self::assertSame('xmldtd', $options->getParser());
    }

    public function testSetParserWithHtml5Alias(): void
    {
        $options = new Options();
        $options->setParser(ParserType::Html5);

        self::assertSame('html5', $options->getParser());
    }

    public function testSetParserWithUnknownValueThrows(): void
    {
        try {
            (new Options())->setParser('bogus');
            self::fail('Expected InvalidArgumentException to be thrown.');
        } catch (\InvalidArgumentException $e) {
            self::assertSame('Unknown parser "bogus".', $e->getMessage());
        }
    }
}
