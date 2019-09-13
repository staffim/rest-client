<?php

namespace Staffim\RestClient;

use Symfony\Component\HttpFoundation\Response as RawResponse;

class Response
{
    /**
     * @var RawResponse
     */
    private $rawResponse;

    /**
     * @var mixed
     */
    private $data;

    public function __construct(RawResponse $response)
    {
        $this->rawResponse = $response;
        $this->data = json_decode($response->getContent(), true);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRawResponse(): RawResponse
    {
        return $this->rawResponse;
    }

    public function getStatusCode(): int
    {
        return $this->getRawResponse()->getStatusCode();
    }

    public function assertStatusCode(int $expectedStatusCode)
    {
        \PHPUnit_Framework_Assert::assertEquals($expectedStatusCode, $this->getStatusCode(), $this->getRawResponse());

        return $this;
    }
}
