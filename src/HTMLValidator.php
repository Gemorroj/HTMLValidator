<?php

namespace HTMLValidator;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HTMLValidator
{
    /**
     * URI to the w3 validator.
     */
    private string $validatorUri = 'https://validator.w3.org/nu/';

    private Options $options;
    private HttpClientInterface $httpClient;

    public function __construct(Options $options = null, HttpClientInterface $httpClient = null)
    {
        $this->options = $options ?: new Options();
        if (!$httpClient) {
            $httpClient = HttpClient::createForBaseUri($this->getValidatorUri(), [
                'headers' => [
                    'User-Agent' => 'gemorroj/htmlvalidator',
                    'Content-Type' => 'text/html; charset=UTF-8',
                ],
            ]);
        }
        $this->httpClient = $httpClient;
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

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(HttpClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

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
     * Validates a given URI.
     *
     * Executes the validator using the current parameters and returns a Response
     * object on success.
     *
     * @param string $uri The address to the page to validate ex: http://example.com/
     *
     * @throws Exception
     * @throws DecodingExceptionInterface    When the body cannot be decoded to an array
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function validateUri(string $uri): Response
    {
        $response = $this->getHttpClient()->request('GET', '', [
            'query' => \array_merge(
                $this->getOptions()->buildOptions(),
                ['doc' => $uri, 'out' => 'json', 'showsource' => 'yes']
            ),
        ]);

        return $this->parseResponse($response->toArray());
    }

    /**
     * Validates the local file.
     *
     * Requests validation on the local file, from an instance of the W3C validator.
     *
     * @param string $file path to file to be validated
     *
     * @throws Exception
     * @throws DecodingExceptionInterface    When the body cannot be decoded to an array
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function validateFile(string $file): Response
    {
        $f = @\fopen($file, 'rb');
        if (false === $f) {
            throw new Exception(\error_get_last()['message']);
        }

        $response = $this->getHttpClient()->request('POST', '', [
            'body' => $f,
            'query' => \array_merge(
                $this->getOptions()->buildOptions(),
                ['out' => 'json', 'showsource' => 'yes']
            ),
        ]);

        return $this->parseResponse($response->toArray());
    }

    /**
     * Validate an html string.
     *
     * @param string $html full html document fragment
     *
     * @throws Exception
     * @throws DecodingExceptionInterface    When the body cannot be decoded to an array
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when $throw is true and the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx when $throw is true
     * @throws ServerExceptionInterface      On a 5xx when $throw is true
     */
    public function validateFragment(string $html): Response
    {
        $response = $this->getHttpClient()->request('POST', '', [
            'body' => $html,
            'query' => \array_merge(
                $this->getOptions()->buildOptions(),
                ['out' => 'json', 'showsource' => 'yes']
            ),
        ]);

        return $this->parseResponse($response->toArray());
    }

    /**
     * Parse an JSON response from the validator.
     *
     * This function parses a JSON response json string from the validator.
     *
     * @throws Exception
     */
    protected function parseResponse(array $data): Response
    {
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
