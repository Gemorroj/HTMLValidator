<?php

namespace HTMLValidator;

class Response
{
    /**
     * the address of the document validated.
     *
     * Will (likely?) be upload://Form Submission
     * if an uploaded document or fragment was validated. In EARL terms, this is
     * the TestSubject.
     *
     * @var string
     */
    protected $uri;

    /**
     * Location of the service which provided the validation result. In EARL terms,
     * this is the Assertor.
     *
     * @var string
     */
    protected $checkedby;

    /**
     * Detected (or forced) Document Type for the validated document.
     *
     * @var string
     */
    protected $doctype;

    /**
     * Detected (or forced) Character Encoding for the validated document.
     *
     * @var string
     */
    protected $charset;

    /**
     * Whether or not the document validated passed or not formal validation
     * (true|false boolean).
     *
     * @var bool
     */
    protected $validity = false;

    /**
     * Array of Error objects (if applicable).
     *
     * @var Error[]
     */
    protected $errors = [];

    /**
     * Array of Warning objects (if applicable).
     *
     * @var Warning[]
     */
    protected $warnings = [];

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     *
     * @return Response
     */
    public function setCharset($charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckedby()
    {
        return $this->checkedby;
    }

    /**
     * @param string $checkedby
     *
     * @return Response
     */
    public function setCheckedby($checkedby): self
    {
        $this->checkedby = $checkedby;

        return $this;
    }

    /**
     * @return string
     */
    public function getDoctype()
    {
        return $this->doctype;
    }

    /**
     * @param string $doctype
     *
     * @return Response
     */
    public function setDoctype($doctype): self
    {
        $this->doctype = $doctype;

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
     *
     * @return Response
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return Response
     */
    public function setUri($uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValidity()
    {
        return $this->validity;
    }

    /**
     * @param bool $validity
     *
     * @return Response
     */
    public function setValidity($validity): self
    {
        $this->validity = $validity;

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
     *
     * @return Response
     */
    public function setWarnings(array $warnings)
    {
        $this->warnings = $warnings;

        return $this;
    }

    /**
     * @return Response
     */
    public function addError(Error $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @return Response
     */
    public function addWarning(Warning $warning): self
    {
        $this->warnings[] = $warning;

        return $this;
    }
}
