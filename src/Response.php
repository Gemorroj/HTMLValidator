<?php

namespace HTMLValidator;

class Response
{
    /**
     * The address of the document validated.
     * If present, must contain the URI (not IRI) of the document being checked
     * or the literal string “data:…” (the last character is U+2026) to signify
     * that the message is associated with a data URI resource but the exact URI
     * has been omitted.
     * If a client application wishes to show IRIs to human users,
     * it is up to the client application to convert the URI into an IRI.
     *
     * @var string|null
     */
    private $uri;

    /**
     * Detected (or forced) Document Type for the validated document.
     *
     * @var string|null
     */
    private $type;

    /**
     * Detected (or forced) Character Encoding for the validated document.
     *
     * @var string|null
     */
    private $encoding;

    /**
     * The "code" string represents the source of the checked document as decoded to Unicode lone surrogates replaced with the REPLACEMENT CHARACTER and with line breaks replaced with U+00A0 LINE FEED.
     *
     * @var string|null
     */
    private $source;

    /**
     * Whether or not the document validated passed or not formal validation.
     *
     * @var bool
     */
    private $valid = false;

    /**
     * Array of Error objects (if applicable).
     *
     * @var Error[]
     */
    private $errors = [];

    /**
     * Array of Warning objects (if applicable).
     *
     * @var Warning[]
     */
    private $warnings = [];

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function setEncoding(?string $encoding): self
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param Error[] $errors
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

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

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * @param Warning[] $warnings
     */
    public function setWarnings(array $warnings): self
    {
        $this->warnings = $warnings;

        return $this;
    }

    public function addError(Error $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    public function addWarning(Warning $warning): self
    {
        $this->warnings[] = $warning;

        return $this;
    }
}
