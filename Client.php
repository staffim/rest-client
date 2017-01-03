<?php

namespace Staffim\RestClient;

class Client
{
    private $baseUrl;

    private $kernel;

    private $client;

    public function __construct($kernel, $baseUrl, array $headers = [])
    {
        $this->kernel = $kernel;
        $this->baseUrl = $baseUrl;
        $this->client = $this->createClient($kernel, $headers);
    }

    private function createClient($kernel, array $headers = [])
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

    public function post($data, $statusCode = 201, array $parameters = [])
    {
        $this->client->request('POST', $this->buildUrl([], $parameters), [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new Response($this->client->getResponse());
    }

    public function put($data, $statusCode = 200, array $parameters = [])
    {
        $this->client->request('PUT', $this->buildUrl([$data['id']], $parameters), [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new Response($this->client->getResponse());
    }

    public function patch($id, array $operations = [], $statusCode = 200, $parameters = [])
    {
        $this->client->request('PATCH', $this->buildUrl([$id], $parameters), [], [], [], json_encode($operations));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new Response($this->client->getResponse());
    }

    public function get($id, $statusCode = 200, array $parameters = [])
    {
        $this->client->request('GET', $this->buildUrl([$id], $parameters));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $response->getContent());

        return new Response($this->client->getResponse());
    }

    public function delete($id, $statusCode = 204, array $parameters = [])
    {
        $this->client->request('DELETE', $this->buildUrl([$id], $parameters));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new Response($this->client->getResponse());
    }

    public function getList(array $parameters = [], $statusCode = 200)
    {
        $this->client->request('GET', $this->buildUrl([], $parameters));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new Response($this->client->getResponse());
    }
}
