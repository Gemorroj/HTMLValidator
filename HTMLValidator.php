<?php
/**
 * This file contains the base class for utilizing an instance of the
 * W3 HTML Validator.
 *
 * PHP versions 5
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 */
namespace HTMLValidator;

/**
 * A simple class for utilizing the W3C HTML Validator service.
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 */
class HTMLValidator
{
    /**
     * URI to the w3 validator.
     * 
     * @var string
     */
    protected $validatorUri = 'http://validator.w3.org/check';
    
    /**
     * HTML fragment to validate.
     *  
     * Full documents only. At the moment, will only work if data is sent with the
     * UTF-8 encoding.
     * @var string
     */
    protected $fragment;
    
    /**
     * Output format
     * 
     * Triggers the various outputs formats of the validator. If unset, the usual 
     * Web format will be sent. If set to soap12, the SOAP1.2 interface will be 
     * triggered. See below for the SOAP 1.2 response format description.
     */
    protected $output = 'soap12';
    
    /**
     * Character encoding
     * 
     * Character encoding override: Specify the character encoding to use when 
     * parsing the document. When used with the auxiliary parameter fbc set to 1, 
     * the given encoding will only be used as a fallback value, in case the charset
     * is absent or unrecognized. Note that this parameter is ignored if validating
     * a fragment with the direct input interface.
     * @var string
     */
    protected $charset;
    
    /**
     * Fall Back Character Set
     * 
     * When no character encoding is detected in the document, use the value in 
     * $charset as the fallback character set.
     *
     * @var bool
     */
    protected $fbc;
    
    /**
     * Document type
     * 
     * Document Type override: Specify the Document Type (DOCTYPE) to use when 
     * parsing the document. When used with the auxiliary parameter fbd set to 1, 
     * the given document type will only be used as a fallback value, in case the
     * document's DOCTYPE declaration is missing or unrecognized.
     * @var string
     */
    protected $doctype;
    
    /**
     * Fall back doctype
     * 
     * When set to 1, use the value stored in $doctype when the document type
     * cannot be automatically determined.
     * 
     * @var bool
     */
    protected $fbd;
    
    /**
     * Verbose output
     * 
     * In the web interface, when set to 1, will make error messages, explanations 
     * and other diagnostics more verbose.
     * In SOAP output, does not have any impact.
     * @var bool
     */
    protected $verbose = false;
    
    /**
     * Show source
     * 
     * In the web interface, triggers the display of the source after the validation
     * results. In SOAP output, does not have any impact.
     * @var bool
     */
    protected $ss = false;
    
    /**
     * outline
     * 
     * In the web interface, when set to 1, triggers the display of the document 
     * outline after the validation results. In SOAP output, does not have any
     * impact.
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
     * @return HTMLValidator
     */
    public function setCharset($charset)
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
     * @return HTMLValidator
     */
    public function setDoctype($doctype)
    {
        $this->doctype = $doctype;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isFbc()
    {
        return $this->fbc;
    }

    /**
     * @param boolean $fbc
     *
     * @return HTMLValidator
     */
    public function setFbc($fbc)
    {
        $this->fbc = $fbc;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isFbd()
    {
        return $this->fbd;
    }

    /**
     * @param boolean $fbd
     *
     * @return HTMLValidator
     */
    public function setFbd($fbd)
    {
        $this->fbd = $fbd;

        return $this;
    }

    /**
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param string $fragment
     *
     * @return HTMLValidator
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isOutline()
    {
        return $this->outline;
    }

    /**
     * @param boolean $outline
     *
     * @return HTMLValidator
     */
    public function setOutline($outline)
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
     * @return HTMLValidator
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSs()
    {
        return $this->ss;
    }

    /**
     * @param boolean $ss
     *
     * @return HTMLValidator
     */
    public function setSs($ss)
    {
        $this->ss = $ss;

        return $this;
    }

    /**
     * @return string
     */
    public function getValidatorUri()
    {
        return $this->validatorUri;
    }

    /**
     * @param string $validatorUri
     *
     * @return HTMLValidator
     */
    public function setValidatorUri($validatorUri)
    {
        $this->validatorUri = $validatorUri;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * @param boolean $verbose
     *
     * @return HTMLValidator
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;

        return $this;
    }


    /**
     * @return array
     */
    protected function buildOptions()
    {
        return array(
            'charset' => $this->getCharset(),
            'fbc' => $this->isFbc() ? '1' : '0',
            'doctype' => $this->getDoctype(),
            'fbd' => $this->isFbd() ? '1' : '0',
            'verbose' => $this->isVerbose() ? '1' : '0',
            'ss' => $this->isSs() ? '1': '0',
            'outline' => $this->isOutline() ? '1' : '0',
            'output' => $this->getOutput()
        );
    }


    /**
     * @param string $uri
     * @param resource $context
     * @return string
     * @throws HTMLValidator\Exception
     */
    protected function sendRequest($uri, $context = null)
    {
        $data = file_get_contents($uri, null, $context);
        if ($data === false) {
            throw new HTMLValidator\Exception('Error send request');
        }

        return $data;
    }
    
    /**
     * Validates a given URI
     * 
     * Executes the validator using the current parameters and returns a Response 
     * object on success.
     *
     * @param string $uri The address to the page to validate ex: http://example.com/
     * 
     * @return HTMLValidator\Response object HTMLValidator\Response
     */
    public function validate($uri)
    {
        $query = http_build_query(array_merge(
            $this->buildOptions(),
            array('uri' => $uri)
        ));
        $data = $this->sendRequest($this->validatorUri . '?' . $query);

        return $this->parseSOAP12Response($data);
    }
    
    /**
     * Validates the local file
     * 
     * Requests validation on the local file, from an instance of the W3C validator.
     * 
     * @param string $file file to be validated.
     *
     * @throws HTMLValidator\Exception
     *
     * @return HTMLValidator\Response object HTMLValidator\Response
     */
    public function validateFile($file)
    {
        if (file_exists($file) !== true) {
            throw new HTMLValidator\Exception('File not found');
        }
        if (is_readable($file) !== true) {
            throw new HTMLValidator\Exception('File not readable');
        }

        $data = file_get_contents($file);
        if ($data === false) {
            throw new HTMLValidator\Exception('Failed get file');
        }

        return $this->validateFragment($data);
    }
    
    /**
     * Validate an html string
     * 
     * @param string $html full html document fragment
     * 
     * @return HTMLValidator\Response object HTMLValidator\Response
     */
    public function validateFragment($html)
    {
        $query = http_build_query(array_merge(
            $this->buildOptions(),
            array('fragment' => $html)
        ));

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $query
            )
        ));

        $data = $this->sendRequest($this->validatorUri, $context);

        return $this->parseSOAP12Response($data);
    }
    
    /**
     * Parse an XML response from the validator
     * 
     * This function parses a SOAP 1.2 response xml string from the validator.
     *
     * @param string $xml The raw soap12 XML response from the validator.
     * 
     * @return HTMLValidator\Response object HTMLValidator\Response
     *
     * @throws HTMLValidator\Exception
     */
    protected function parseSOAP12Response($xml)
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        if ($doc->loadXML($xml) == false) {
            throw new HTMLValidator\Exception('Failed load xml');
        }

        $response = new HTMLValidator\Response();

        // Get the standard CDATA elements
        foreach (array('uri', 'checkedby', 'doctype', 'charset') as $var) {
            $element = $doc->getElementsByTagName($var);
            if ($element->length) {
                $response->{'set' . ucfirst($var)}($element->item(0)->nodeValue);
            }
        }

        // Handle the bool element validity
        $element = $doc->getElementsByTagName('validity');
        if ($element->length && $element->item(0)->nodeValue == 'true') {
            $response->setValidity(true);
        } else {
            $response->setValidity(false);
        }

        if (!$response->isValidity()) {
            $errors = $doc->getElementsByTagName('error');
            foreach ($errors as $error) {
                $response->addError(new HTMLValidator\Error($error));
            }
        }
        $warnings = $doc->getElementsByTagName('warning');
        foreach ($warnings as $warning) {
            $response->addWarning(new HTMLValidator\Warning($warning));
        }

        return $response;
    }
}
