<?php

namespace HTMLValidator;

class HTMLValidator
{
    /**
     * URI to the w3 validator.
     *
     * @var string
     */
    private $validatorUri = 'http://validator.w3.org/nu/';

    /**
     * @var Options
     */
    private $options;

    /**
     * Default context for http request.
     *
     * @see https://www.php.net/manual/en/context.php
     *
     * @var array
     */
    private $context = [];

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

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;

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
     * @throws Exception
     */
    protected function sendRequest(string $uri, array $context): string
    {
        $context = \array_merge($this->getContext(), $context);

        if (isset($context['http']['header'])) {
            $context['http']['header'] .= "\r\nUser-Agent: gemorroj/htmlvalidator";
        } else {
            $context['http']['header'] = 'User-Agent: gemorroj/htmlvalidator';
        }

        $data = @\file_get_contents($uri, false, \stream_context_create($context));
        if (false === $data) {
            throw new Exception(\error_get_last()['message']);
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
     * @throws Exception|\JsonException
     */
    public function validateUri(string $uri): Response
    {
        $query = \http_build_query(\array_merge(
            $this->getOptions()->buildOptions(),
            ['doc' => $uri, 'out' => 'json', 'showsource' => 'yes']
        ));

        $context = [
            'http' => [
                'method' => 'GET',
                'header' => 'Content-Type: text/html; charset=utf-8',
            ],
        ];

        $data = $this->sendRequest($this->validatorUri.'?'.$query, $context);

        return $this->parseJsonResponse($data);
    }

    /**
     * Validates the local file.
     *
     * Requests validation on the local file, from an instance of the W3C validator.
     *
     * @param string $file file to be validated
     *
     * @throws Exception|\JsonException
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

        $data = @\file_get_contents($file);
        if (false === $data) {
            throw new Exception(\error_get_last()['message']);
        }

        return $this->validateFragment($data);
    }

    /**
     * Validate an html string.
     *
     * @param string $html full html document fragment
     *
     * @throws Exception|\JsonException
     *
     * @return Response object HTMLValidator\Response
     */
    public function validateFragment(string $html): Response
    {
        $query = \http_build_query(\array_merge(
            $this->getOptions()->buildOptions(),
            ['out' => 'json', 'showsource' => 'yes']
        ));

        $context = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: text/html; charset=utf-8',
                'content' => $html,
            ],
        ];

        $data = $this->sendRequest($this->validatorUri.'?'.$query, $context);

        return $this->parseJsonResponse($data);
    }

    /**
     * Parse an JSON response from the validator.
     *
     * This function parses a JSON response json string from the validator.
     *
     * @param string $json the raw JSON response from the validator
     *
     * @throws Exception|\JsonException
     */
    protected function parseJsonResponse(string $json): Response
    {
        $data = \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);

        $response = new Response();

        $response->setEncoding($data['source']['encoding'] ?? null);
        $response->setType($data['source']['type'] ?? null);
        $response->setUri($data['url'] ?? null);
        $response->setSource($data['source']['code'] ?? null);

        if (isset($data['messages'][0]['type']) && 'non-document-error' === $data['messages'][0]['type']) {
            throw new Exception($data['messages'][0]['message']);
        }

        $response->setValid(true);
        foreach ($data['messages'] as $message) {
            if ('error' === $message['type']) {
                $response->setValid(false);
                $response->addError(new Error($message));
            }
            if ('info' === $message['type']) { // warning
                $response->addWarning(new Warning($message));
            }
        }

        return $response;
    }
}
