<?php

namespace Hatchery;

use Exception;
use Hatchery\Connection\Curl\CurlPost;
use Hatchery\Connection\ResponseException;
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

                $e = new Connection\ResponseException(sprintf('[%s]: Send failed: [%s]', $response->getStatusCode(), $response->getContent()));
                $e->setResponse($response);
                throw $e;
            }
        } catch (Exception $e) {
            throw new ResponseException($e->getMessage(), $response);
        }
    }

}
