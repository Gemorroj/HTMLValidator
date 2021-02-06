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

    public function __construct(Options $options = null)
    {
        $this->setOptions($options ?: new Options());
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function setOptions(Options $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getValidatorUri(): string
    {
        return $this->validatorUri;
    }

    public function setValidatorUri(string $validatorUri): self
    {
        $this->validatorUri = $validatorUri;

        return $this;
    }

    /**
     * @param resource $context
     *
     * @throws Exception
     */
    protected function sendRequest(string $uri, $context = null): string
    {
        $data = \file_get_contents($uri, null, $context);
        if (false === $data) {
            throw new Exception('Error send request');
        }

        return $data;
    }

    /**
     * Validates a given URI.
     *
     * Executes the validator using the current parameters and returns a Response
     * object on success.
     *
     * @param string $uri The address to the page to validate ex: http://example.com/
     *
     * @throws Exception
     *
     * @return Response object HTMLValidator\Response
     */
    public function validateUri(string $uri): Response
    {
        $query = \http_build_query(\array_merge(
            $this->getOptions()->buildOptions(),
            ['uri' => $uri]
        ));

        $context = \stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: HTMLValidator',
            ],
        ]);

        $data = $this->sendRequest($this->validatorUri.'?'.$query, $context);

        return $this->parseSOAP12Response($data);
    }

    /**
     * Validates the local file.
     *
     * Requests validation on the local file, from an instance of the W3C validator.
     *
     * @param string $file file to be validated
     *
     * @throws Exception
     *
     * @return Response object HTMLValidator\Response
     */
    public function validateFile(string $file): Response
    {
        if (true !== \file_exists($file)) {
            throw new Exception('File not found');
        }
        if (true !== \is_readable($file)) {
            throw new Exception('File not readable');
        }

        $data = \file_get_contents($file);
        if (false === $data) {
            throw new Exception('Failed get file');
        }

        return $this->validateFragment($data);
    }

    /**
     * Validate an html string.
     *
     * @param string $html full html document fragment
     *
     * @throws Exception
     *
     * @return Response object HTMLValidator\Response
     */
    public function validateFragment(string $html): Response
    {
        $query = \http_build_query(\array_merge(
            $this->getOptions()->buildOptions(),
            ['fragment' => $html]
        ));

        $context = \stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\nUser-Agent: HTMLValidator",
                'content' => $query,
            ],
        ]);

        $data = $this->sendRequest($this->validatorUri, $context);

        return $this->parseSOAP12Response($data);
    }

    /**
     * Parse an XML response from the validator.
     *
     * This function parses a SOAP 1.2 response xml string from the validator.
     *
     * @param string $xml the raw soap12 XML response from the validator
     *
     * @throws Exception
     *
     * @return Response object HTMLValidator\Response
     */
    protected function parseSOAP12Response(string $xml): Response
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        if (false === $doc->loadXML($xml)) {
            throw new Exception('Failed load xml');
        }

        $response = new Response();

        // Get the standard CDATA elements
        foreach (['uri', 'checkedby', 'doctype', 'charset'] as $var) {
            $element = $doc->getElementsByTagName($var);
            if ($element->length) {
                $response->{'set'.\ucfirst($var)}($element->item(0)->nodeValue);
            }
        }

        // Handle the bool element validity
        $element = $doc->getElementsByTagName('validity');
        if ($element->length && 'true' === $element->item(0)->nodeValue) {
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
