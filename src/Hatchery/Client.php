<?php

namespace Hatchery;

use Hatchery\Connection\Curl\CurlPost;
use Hatchery\Payload\JobAdd;
use Hatchery\Payload\JobStatus;
use Hatchery\Payload\Payload;

class Client {

    private $baseLink;
    private $interface;
    private $apiKey;

    public function __construct($apiUrl, $apiKey) {
        $this->baseLink = rtrim($apiUrl, '/');
        $this->apiKey = $apiKey;
        $this->interface = new CurlPost;
    }

    public function createJobAddPayload($preset, $uriInput, $uriOutput) {
        return new JobAdd($this->baseLink . '/api/v2/jobs/', $preset, $uriInput, $uriOutput);
    }

    public function createJobStatusPayload($identifier) {
        return new JobStatus($this->baseLink, $identifier);
    }

    public function sendPayload(Payload $payload) {
        $payload->setHeader('x-auth-token', $this->apiKey);
        $payload->setHeader('Content-Type', 'application/json');
        /* @var $response \Hatchery\Connection\ResponseInterface */
        $response = $this->interface->sendPayload($payload);
        try {

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {

                return $response;
            } else {

                $ex = new Connection\ResponseException(sprintf('[%s]: Send failed: [%s]', $response->getStatusCode(), $response->getContent()));
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
