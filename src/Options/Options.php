<?php

namespace HTMLValidator\Options;

class Options
{
    /**
     * Parser type. Default is {@see ParserType::Html5}.
     */
    private string $parser = ParserType::Html5->value;

    public function getParser(): string
    {
        return $this->parser;
    }

    /**
     * @throws \InvalidArgumentException when an unknown parser name is given
     */
    public function setParser(ParserType|string $parser): self
    {
        if (\is_string($parser)) {
            $parser = ParserType::tryFrom($parser) ?? throw new \InvalidArgumentException(\sprintf('Unknown parser "%s".', $parser));
        }

        $this->parser = $parser->value;

        return $this;
    }

    /**
     * @return array{parser: string}
     */
    public function buildOptions(): array
    {
        return [
            'parser' => $this->getParser(),
        ];
    }
}
