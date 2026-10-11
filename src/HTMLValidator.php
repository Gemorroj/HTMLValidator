<?php

namespace HTMLValidator;

use HTMLValidator\Message\Error;
use HTMLValidator\Message\Warning;
use HTMLValidator\Options\Options;
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
    private string $validatorUri;

    private Options $options;
    private HttpClientInterface $httpClient;

    public function __construct(
        ?Options $options = null,
        ?HttpClientInterface $httpClient = null,
        string $validatorUri = 'https://validator.w3.org/nu/',
    ) {
        $this->options = $options ?? new Options();
        $this->httpClient = $httpClient ?? HttpClient::create();
        $this->validatorUri = $validatorUri;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function getValidatorUri(): string
    {
        return $this->validatorUri;
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
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    public function validateUri(string $uri): Response
    {
        return $this->request('GET', \array_merge($this->buildQuery(), ['doc' => $uri]));
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
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    public function validateFile(string $file): Response
    {
        $f = @\fopen($file, 'r');
        if (false === $f) {
            throw new Exception(\error_get_last()['message'] ?? \sprintf('Unable to open file "%s".', $file));
        }

        try {
            return $this->request('POST', $this->buildQuery(), $f);
        } finally {
            \fclose($f);
        }
    }

    /**
     * Validate an html string.
     *
     * @param string $html full html document fragment
     *
     * @throws Exception
     * @throws DecodingExceptionInterface    When the body cannot be decoded to an array
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    public function validateFragment(string $html): Response
    {
        return $this->request('POST', $this->buildQuery(), $html);
    }

    /**
     * Send a request to the validator and parse its JSON response.
     *
     * @param array<string, string> $query
     * @param string|resource|null  $body
     *
     * @throws Exception
     * @throws DecodingExceptionInterface    When the body cannot be decoded to an array
     * @throws TransportExceptionInterface   When a network error occurs
     * @throws RedirectionExceptionInterface On a 3xx when the "max_redirects" option has been reached
     * @throws ClientExceptionInterface      On a 4xx
     * @throws ServerExceptionInterface      On a 5xx
     */
    private function request(string $method, array $query, mixed $body = null): Response
    {
        $options = [
            'headers' => [
                'User-Agent' => 'gemorroj/htmlvalidator',
                'Content-Type' => 'text/html; charset=UTF-8',
            ],
            'query' => $query,
        ];
        if (null !== $body) {
            $options['body'] = $body;
        }

        $response = $this->getHttpClient()->request($method, $this->getValidatorUri(), $options);

        return $this->parseResponse($response->toArray());
    }

    /**
     * Build the common query string arguments for the validator.
     *
     * @return array<string, string>
     */
    private function buildQuery(): array
    {
        return \array_merge(
            $this->getOptions()->buildOptions(),
            ['out' => 'json', 'showsource' => 'yes']
        );
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
        $messages = $data['messages'] ?? [];

        foreach ($messages as $message) {
            if ('non-document-error' === ($message['type'] ?? null)) {
                throw new Exception($message['message'] ?? 'The validator reported a non-document error.');
            }
        }

        $response = new Response();

        $response->setEncoding($data['source']['encoding'] ?? null);
        $response->setType($data['source']['type'] ?? null);
        $response->setUri($data['url'] ?? null);
        $response->setSource($data['source']['code'] ?? null);

        $response->setValid(true);
        foreach ($messages as $message) {
            if ('error' === ($message['type'] ?? null)) {
                $response->setValid(false);
                $response->addError(new Error($message));
            } elseif ('info' === ($message['type'] ?? null)) { // warning
                $response->addWarning(new Warning($message));
            }
        }

        return $response;
    }
}
