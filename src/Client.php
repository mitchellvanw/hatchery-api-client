<?php

namespace Hatchery;

use Hatchery\Connection\Curl\CurlPost;
use Hatchery\Payload\Payload as PayloadItem;

class Client
{

    private $baseLink;
    private $interface;
    private $apiKey;

    public function __construct($api, $apiKey)
    {
        $this->baseLink     = rtrim($api, '/') . '/';
        $this->apiKey       = $apiKey;
        $this->interface    = new CurlPost();
    }

    public function createJobAddPayload($preset, $ftpIn, $ftpOut, $options = array())
    {
        return new Payload\JobAdd($this->baseLink . 'api/jobs/', $preset, $ftpIn, $ftpOut, $options);
    }

    public function createJobStatusPayload($identifier)
    {
        return new Payload\JobStatus($this->baseLink . 'api/jobs/', $identifier);
    }


    public function sendPayload(PayloadItem $payload, $header = 'x-auth-token')
    {
        $payload->setHeader($header, $this->apiKey());
        
        $response = $this->interface->sendPayload($payload);
        /* @var $response \Hatchery\Connection\ResponseInterface */
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $response->getJsonResponse();
        } else {
            $ex = new Connection\ResponseException('Send failed');
            $ex->setResponse($response);
            throw $ex;
        }
    }


}
