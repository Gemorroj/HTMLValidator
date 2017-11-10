<?php

namespace HTMLValidator;

class HTMLValidator
{
    /**
     * URI to the w3 validator.
     * 
     * @var string
     */
    protected $validatorUri = 'http://validator.w3.org/check';

    /**
     * @var Options
     */
    protected $options;

    /**
     * @param Options $options
     */
    public function __construct(Options $options = null)
    {
        $this->setOptions($options === null ? new Options() : $options);
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options $options
     *
     * @return HTMLValidator
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;

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
     * @param string $uri
     * @param resource $context
     * @return string
     * @throws Exception
     */
    protected function sendRequest($uri, $context = null)
    {
        $data = file_get_contents($uri, null, $context);
        if ($data === false) {
            throw new Exception('Error send request');
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
     * @return Response object HTMLValidator\Response
     */
    public function validateUri($uri)
    {
        $query = http_build_query(array_merge(
            $this->getOptions()->buildOptions(),
            array('uri' => $uri)
        ));

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'User-Agent: HTMLValidator',
            )
        ));

        $data = $this->sendRequest($this->validatorUri . '?' . $query, $context);

        return $this->parseSOAP12Response($data);
    }
    
    /**
     * Validates the local file
     * 
     * Requests validation on the local file, from an instance of the W3C validator.
     * 
     * @param string $file file to be validated.
     *
     * @throws Exception
     *
     * @return Response object HTMLValidator\Response
     */
    public function validateFile($file)
    {
        if (file_exists($file) !== true) {
            throw new Exception('File not found');
        }
        if (is_readable($file) !== true) {
            throw new Exception('File not readable');
        }

        $data = file_get_contents($file);
        if ($data === false) {
            throw new Exception('Failed get file');
        }

        return $this->validateFragment($data);
    }
    
    /**
     * Validate an html string
     * 
     * @param string $html full html document fragment
     * 
     * @return Response object HTMLValidator\Response
     */
    public function validateFragment($html)
    {
        $query = http_build_query(array_merge(
            $this->getOptions()->buildOptions(),
            array('fragment' => $html)
        ));

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\nUser-Agent: HTMLValidator",
                'content' => $query,
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
     * @return Response object HTMLValidator\Response
     *
     * @throws Exception
     */
    protected function parseSOAP12Response($xml)
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        if ($doc->loadXML($xml) === false) {
            throw new Exception('Failed load xml');
        }

        $response = new Response();

        // Get the standard CDATA elements
        foreach (array('uri', 'checkedby', 'doctype', 'charset') as $var) {
            $element = $doc->getElementsByTagName($var);
            if ($element->length) {
                $response->{'set' . ucfirst($var)}($element->item(0)->nodeValue);
            }
        }

        // Handle the bool element validity
        $element = $doc->getElementsByTagName('validity');
        if ($element->length && $element->item(0)->nodeValue === 'true') {
            $response->setValidity(true);
        } else {
            $response->setValidity(false);
        }

        if (!$response->isValidity()) {
            $errors = $doc->getElementsByTagName('error');
            foreach ($errors as $error) {
                $response->addError(new Error($error));
            }
        }
        $warnings = $doc->getElementsByTagName('warning');
        foreach ($warnings as $warning) {
            $response->addWarning(new Warning($warning));
        }

        return $response;
    }
}