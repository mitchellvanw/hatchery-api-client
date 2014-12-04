<?php

namespace Hatchery;

use Hatchery\Connection\Curl\CurlPost;
use Hatchery\Payload\Payload as PayloadItem;

class Client {

    private $baseLink;
    private $interface;
    private $apiKey;

    public function __construct($api, $apiKey) {
        $this->baseLink = rtrim($api, '/');
        $this->apiKey = $apiKey;
        $this->interface = new CurlPost();
    }

    public function createJobAddPayload($preset, $ftpIn, $ftpOut, $options = array()) {
        return new Payload\JobAdd($this->baseLink . '/api/jobs/', $preset, $ftpIn, $ftpOut, $options);
    }

    public function createJobStatusPayload($identifier) {
        return new Payload\JobStatus($this->baseLink, $identifier);
    }

    public function sendPayload(PayloadItem $payload) {
        $payload->setHeader('x-auth-token', $this->apiKey);
        /* @var $response \Hatchery\Connection\ResponseInterface */
        $response = $this->interface->sendPayload($payload);
        try {
            
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                
                return $response;
            } else {
                $ex = new Connection\ResponseException(sprintf('[%s]: Send failed', $response->getStatusCode()));
                $ex->setResponse($response);
                throw $ex;
            }
        } catch (\Exception $ex) {
            $ex = new Connection\ResponseException($ex->getMessage());
            $ex->setResponse($response);
            throw $ex;
        }
    }

}
