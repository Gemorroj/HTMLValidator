<?php

namespace HTMLValidator;

class Options
{
    /**
     * Output format.
     *
     * Triggers the various outputs formats of the validator. If unset, the usual
     * Web format will be sent. If set to soap12, the SOAP1.2 interface will be
     * triggered. See below for the SOAP 1.2 response format description.
     */
    protected $output = 'soap12';

    /**
     * Character encoding.
     *
     * Character encoding override: Specify the character encoding to use when
     * parsing the document. When used with the auxiliary parameter fbc set to 1,
     * the given encoding will only be used as a fallback value, in case the charset
     * is absent or unrecognized. Note that this parameter is ignored if validating
     * a fragment with the direct input interface.
     *
     * @var string
     */
    protected $charset;

    /**
     * Fall Back Character Set.
     *
     * When no character encoding is detected in the document, use the value in
     * $charset as the fallback character set.
     *
     * @var bool
     */
    protected $fbc = false;

    /**
     * Document type.
     *
     * Document Type override: Specify the Document Type (DOCTYPE) to use when
     * parsing the document. When used with the auxiliary parameter fbd set to 1,
     * the given document type will only be used as a fallback value, in case the
     * document's DOCTYPE declaration is missing or unrecognized.
     *
     * @var string
     */
    protected $doctype;

    /**
     * Fall back doctype.
     *
     * When set to 1, use the value stored in $doctype when the document type
     * cannot be automatically determined.
     *
     * @var bool
     */
    protected $fbd = false;

    /**
     * Verbose output.
     *
     * In the web interface, when set to 1, will make error messages, explanations
     * and other diagnostics more verbose.
     * In SOAP output, does not have any impact.
     *
     * @var bool
     */
    protected $verbose = false;

    /**
     * Show source.
     *
     * In the web interface, triggers the display of the source after the validation
     * results. In SOAP output, does not have any impact.
     *
     * @var bool
     */
    protected $ss = false;

    /**
     * outline.
     *
     * In the web interface, when set to 1, triggers the display of the document
     * outline after the validation results. In SOAP output, does not have any
     * impact.
     *
     * @var bool
     */
    protected $outline = false;

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
     * @return Options
     */
    public function setCharset($charset): self
    {
        $this->charset = $charset;

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
     * @return Options
     */
    public function setDoctype($doctype): self
    {
        $this->doctype = $doctype;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFbc()
    {
        return $this->fbc;
    }

    /**
     * @param bool $fbc
     *
     * @return Options
     */
    public function setFbc($fbc): self
    {
        $this->fbc = $fbc;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFbd()
    {
        return $this->fbd;
    }

    /**
     * @param bool $fbd
     *
     * @return Options
     */
    public function setFbd($fbd)
    {
        $this->fbd = $fbd;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOutline()
    {
        return $this->outline;
    }

    /**
     * @param bool $outline
     *
     * @return Options
     */
    public function setOutline($outline): self
    {
        $this->outline = $outline;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     *
     * @return Options
     */
    public function setOutput($output): self
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSs()
    {
        return $this->ss;
    }

    /**
     * @param bool $ss
     *
     * @return Options
     */
    public function setSs($ss): self
    {
        $this->ss = $ss;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * @param bool $verbose
     *
     * @return Options
     */
    public function setVerbose($verbose): self
    {
        $this->verbose = $verbose;

        return $this;
    }

    public function buildOptions(): array
    {
        return [
            'charset' => $this->getCharset(),
            'fbc' => $this->isFbc() ? '1' : '0',
            'doctype' => $this->getDoctype(),
            'fbd' => $this->isFbd() ? '1' : '0',
            'verbose' => $this->isVerbose() ? '1' : '0',
            'ss' => $this->isSs() ? '1' : '0',
            'outline' => $this->isOutline() ? '1' : '0',
            'output' => $this->getOutput(),
        ];
    }
}
