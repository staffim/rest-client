<?php

namespace Staffim\RestClient;

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
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    public function __construct(KernelInterface $kernel, $baseUrl, array $headers = [])
    {
        $this->kernel = $kernel;
        $this->baseUrl = $baseUrl;
        $this->client = $this->createClient($kernel, $headers);
    }

    public function post($data, $statusCode = 201, array $parameters = [], array $extraUrl = [])
    {
        $this->client->request('POST', $this->buildUrl($extraUrl, $parameters), [], [], [], json_encode($data));

        return $this->createResponse($statusCode);
    }

    public function put($data, $statusCode = 200, array $parameters = [], array $extraUrl = [])
    {
        $this->client->request('PUT', $this->buildUrl(array_merge([$data['id']], $extraUrl), $parameters), [], [], [], json_encode($data));

        return $this->createResponse($statusCode);
    }

    public function patch($id, array $operations = [], $statusCode = 200, $parameters = [], array $extraUrl = [])
    {
        $this->client->request(
            'PATCH',
            $this->buildUrl(array_merge([$id], $extraUrl), $parameters),
            [],
            [],
            [],
            json_encode($operations)
        );

        return $this->createResponse($statusCode);
    }

    public function get($id, $statusCode = 200, array $parameters = [], array $extraUrl = [])
    {
        $this->client->request('GET', $this->buildUrl(array_merge([$id], $extraUrl), $parameters));

        return $this->createResponse($statusCode);
    }

    public function delete($id, $statusCode = 204, array $parameters = [], array $extraUrl = [])
    {
        $this->client->request('DELETE', $this->buildUrl(array_merge([$id], $extraUrl), $parameters));

        return $this->createResponse($statusCode);
    }

    public function getList(array $parameters = [], $statusCode = 200, array $extraUrl = [])
    {
        $this->client->request('GET', $this->buildUrl($extraUrl, $parameters));
        return $this->createResponse($statusCode);
    }

    public function file(array $files, $statusCode = 201, $data = [], array $parameters = [], array $extraUrl = [])
    {
        $this->client->request(
            'POST',
            $this->buildUrl($extraUrl, $parameters),
            [],
            $files,
            [],
            json_encode($data)
        );

        return $this->createResponse($statusCode);
    }

    private function createResponse($statusCode = 200)
    {
        $response = $this->client->getResponse();

        if ($statusCode !== $response->getStatusCode()) {
            throw new RestException($this->client);
        }

        return new Response($response);
    }

    private function createClient(KernelInterface $kernel, array $headers = [])
    {
        $client = $kernel->getContainer()->get('test.client');
        $client->setServerParameters($headers);

        return $client;
    }

    private function buildUrl(array $parts = [], array $parameters = [])
    {
        $url = implode('/', array_merge([$this->baseUrl], $parts));
        if ($parameters) {
            $url .= '?' . http_build_query($parameters);
        }

        return $url;
    }
}
