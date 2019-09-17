<?php

namespace Staffim\RestClient;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpKernel\KernelInterface;

class Client
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    private $kernel;

    /**
     * @var KernelBrowser
     */
    private $kernelBrowser;

    public function __construct(KernelInterface $kernel, $baseUrl, array $headers = [])
    {
        $this->kernel = $kernel;
        $this->baseUrl = $baseUrl;
        $this->kernelBrowser = $this->createBrowser($kernel, $headers);
    }

    public function post($data, $statusCode = 201, array $options = []): Response
    {
        $this->kernelBrowser->request(
            'POST',
            $this->buildUrlFromOptions($options),
            $options['parameters'] ?? [],
            $options['files'] ?? [],
            $options['server'] ?? [],
            is_array($data) ? json_encode($data) : $data
        );

        return $this->createResponse($statusCode);
    }

    public function put($data, $statusCode = 200, array $options = []): Response
    {
        $this->kernelBrowser->request(
            'PUT',
            $this->buildUrlFromOptions($options, [$data['id']]),
            $options['parameters'] ?? [],
            $options['files'] ?? [],
            $options['server'] ?? [],
            is_array($data) ? json_encode($data) : $data
        );

        return $this->createResponse($statusCode);
    }

    public function patch($id, array $operations = [], $statusCode = 200, array $options = []): Response
    {
        $this->kernelBrowser->request(
            'PATCH',
            $this->buildUrlFromOptions($options, [$id]),
            $options['parameters'] ?? [],
            $options['files'] ?? [],
            $options['server'] ?? [],
            is_array($operations) ? json_encode($operations) : $operations
        );

        return $this->createResponse($statusCode);
    }

    public function get($id, $statusCode = 200, array $options = []): Response
    {
        $this->kernelBrowser->request('GET', $this->buildUrl(array_merge([$id], $options['extraUrl'] ?? []), $options['query'] ?? []));

        return $this->createResponse($statusCode);
    }

    public function delete($id, $statusCode = 204, array $options = [])
    {
        $this->kernelBrowser->request('DELETE', $this->buildUrl(array_merge([$id], $options['extraUrl'] ?? []), $options['query'] ?? []));

        return $this->createResponse($statusCode);
    }

    public function getList(array $parameters = [], $statusCode = 200, array $options = []): Response
    {
        $this->kernelBrowser->request('GET', $this->buildUrl($options['extraUrl'] ?? [], $parameters));

        return $this->createResponse($statusCode);
    }

    private function createResponse($statusCode = 200): Response
    {
        $response = $this->kernelBrowser->getResponse();

        if ($statusCode !== $response->getStatusCode()) {
            throw new RestException($this->kernelBrowser);
        }

        return new Response($response);
    }

    private function createBrowser(KernelInterface $kernel, array $headers = []): KernelBrowser
    {
        $browser = $kernel->getContainer()->get('test.client');
        $browser->setServerParameters($headers);

        return $browser;
    }

    private function buildUrlFromOptions(array $options = [], array $additionalParams = []): string
    {
        return $this->buildUrl(array_merge($additionalParams, $options['extraUrl'] ?? []), $options['query'] ?? []);
    }

    private function buildUrl(array $parts = [], array $parameters = []): string
    {
        $url = implode('/', array_merge([$this->baseUrl], $parts));
        if ($parameters) {
            $url .= '?' . http_build_query($parameters);
        }

        return $url;
    }
}
