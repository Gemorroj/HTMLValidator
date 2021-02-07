<?php

namespace HTMLValidator;

abstract class Message
{
    /**
     * The "lastLine" number indicates the last line (inclusive) onto which the source range associated with the message falls.
     *
     * @var int|null
     */
    private $lastLine;
    /**
     * The "firstLine" number indicates the first line onto which the source range associated with the message falls. If the attribute is missing, it is assumed to have the same value as "lastLine".
     *
     * @var int|null
     */
    private $firstLine;

    /**
     * The "lastColumn" number indicates the last column (inclusive) onto which the source range associated with the message falls on the last line onto which is falls.
     *
     * @var int|null
     */
    private $lastColumn;
    /**
     * The "firstColumn" number indicates the first column onto which the source range associated with the message falls on the first line onto which is falls.
     *
     * @var int|null
     */
    private $firstColumn;

    /**
     * The "message" string represents a paragraph of text (suitable for rendering to the user as plain text without further processing) that is the message stated succinctly in natural language.
     *
     * @var string
     */
    private $message;

    /**
     * The "extract" string represents an extract of the document source from around the point in source designated for the message by the "line" and "column" numbers.
     *
     * @var string|null
     */
    private $extract;

    /**
     * @var int|null
     */
    private $hiliteStart;

    /**
     * @var int|null
     */
    private $hiliteLength;

    /**
     * If the "url" string is absent on the message element but present on the root element, the message is considered to be associated with the resource designated by the attribute on the root element.
     *
     * @var string|null
     */
    private $uri;

    /**
     * Constructor for a response message.
     *
     * @param array $message a JSON document node
     */
    public function __construct(array $message)
    {
        $this->setUri($message['url'] ?? null);
        $this->setExtract($message['extract'] ?? null);
        $this->setFirstColumn($message['firstColumn'] ?? null);
        $this->setLastColumn($message['lastColumn'] ?? null);
        $this->setFirstLine($message['firstLine'] ?? null);
        $this->setLastLine($message['lastLine'] ?? null);
        $this->setMessage($message['message'] ?? null);
        $this->setHiliteLength($message['hiliteLength'] ?? null);
        $this->setHiliteStart($message['hiliteStart'] ?? null);
    }

    public function getLastLine(): ?int
    {
        return $this->lastLine;
    }

    public function setLastLine(?int $lastLine): self
    {
        $this->lastLine = $lastLine;

        return $this;
    }

    public function getFirstLine(): ?int
    {
        return $this->firstLine;
    }

    public function setFirstLine(?int $firstLine): self
    {
        $this->firstLine = $firstLine;

        return $this;
    }

    public function getLastColumn(): ?int
    {
        return $this->lastColumn;
    }

    public function setLastColumn(?int $lastColumn): self
    {
        $this->lastColumn = $lastColumn;

        return $this;
    }

    public function getFirstColumn(): ?int
    {
        return $this->firstColumn;
    }

    public function setFirstColumn(?int $firstColumn): self
    {
        $this->firstColumn = $firstColumn;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getExtract(): ?string
    {
        return $this->extract;
    }

    public function setExtract(?string $extract): self
    {
        $this->extract = $extract;

        return $this;
    }

    public function getHiliteStart(): ?int
    {
        return $this->hiliteStart;
    }

    public function setHiliteStart(?int $hiliteStart): self
    {
        $this->hiliteStart = $hiliteStart;

        return $this;
    }

    public function getHiliteLength(): ?int
    {
        return $this->hiliteLength;
    }

    public function setHiliteLength(?int $hiliteLength): self
    {
        $this->hiliteLength = $hiliteLength;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}
