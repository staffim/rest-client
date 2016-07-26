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

    public function post($data, $statusCode = 201, array $parameters = [])
    {
        $this->client->request('POST', $this->baseUrl, [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new RestResponse($this->client->getResponse());
    }

    public function put($data, $statusCode = 200, array $parameters = [])
    {
        $this->client->request('PUT', $this->baseUrl . '/' . $data['id'], [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new RestResponse($this->client->getResponse());
    }

    public function patch($id, array $operations = [], $statusCode = 200, $parameters = [])
    {
        $this->client->request('PATCH', $this->baseUrl, [], [], [], json_encode($data));
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new RestResponse($this->client->getResponse());
    }

    public function get($id, $statusCode = 200, array $parameters = [])
    {
        $this->client->request('GET', $this->baseUrl . '/' . $id);
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $response->getContent());

        return new RestResponse($this->client->getResponse());
    }

    public function delete($id, $statusCode = 204, array $parameters = [])
    {
        $this->client->request('DELETE', $this->baseUrl . '/' . $id);
        $response = $this->client->getResponse();
        \PHPUnit_Framework_Assert::assertEquals($statusCode, $response->getStatusCode(), $this->client->getResponse());

        return new RestResponse($this->client->getResponse());
    }

    public function getList($statusCode, $statusCode = 200, array $parameters = [])
    {

    }
}
