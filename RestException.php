<?php

namespace Staffim\RestClient;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestException extends \RuntimeException
{
    /**
     * @var KernelBrowser
     */
    private $kernelBrowser;

    /**
     * @param KernelBrowser $kernelBrowser
     */
    public function __construct(KernelBrowser $kernelBrowser)
    {
        $this->kernelBrowser = $kernelBrowser;

        parent::__construct('Unexpected response status code: ' . $kernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): ?Request
    {
        return $this->kernelBrowser->getRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(): ?Response
    {
        return $this->kernelBrowser->getResponse();
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
