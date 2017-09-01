<?php

namespace Staffim\RestClient;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class RestException extends \RuntimeException
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $client;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        parent::__construct('Unexpected response status code: ' . $client->getResponse()->getStatusCode());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): ?Request
    {
        return $this->client->getRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(): ?Response
    {
        return $this->client->getResponse();
    }

    /**
     * @return array|mixed
     */
    public function getResponseData()
    {
        if (!$this->getResponse()) {
            return [];
        }

        return json_decode($this->getResponse()->getContent(), true);
    }
}
