<?php

namespace Staffim\RestClient;

class Response
{
    private $response;

    private $data;

    public function __construct($response)
    {
        $this->response = $response;
        $this->data = json_decode($response->getContent(), true);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getStatusCode()
    {
        return $this->getResponse()->getStatusCode();
    }

    public function assertStatusCode($expectedStatusCode)
    {
        \PHPUnit_Framework_Assert::assertEquals($expectedStatusCode, $this->getStatusCode(), $this->getResponse());

        return $this;
    }
}
