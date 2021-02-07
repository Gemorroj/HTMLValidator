<?php

namespace HTMLValidator;

class Options
{
    /**
     * Parser. default is html5.
     *
     * html - HTML parser.
     * html5 - HTML parser. (The html5 value is just an alias for the html value.)
     * xml - XML parser, will not load external entities.
     * xmldtd - XML parser, will load external entities.
     */
    private $parser = 'html5';

    public function getParser(): string
    {
        return $this->parser;
    }

    public function setParser(string $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    public function buildOptions(): array
    {
        return [
            'parser' => $this->getParser(),
        ];
    }
}
