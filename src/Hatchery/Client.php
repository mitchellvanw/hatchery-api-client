<?php

namespace Hatchery;

use Exception;
use Hatchery\Connection\Curl\CurlPost;
use Hatchery\Connection\ResponseException;
use Hatchery\Connection\Response;
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

    public function sendPayload(Payload $payload) {
        $payload->setHeader('x-auth-token', $this->apiKey);
        /* @var $response Response */
        $response = $this->interface->sendPayload($payload);
        try {
            if ($this->isPayloadSuccessful($response)) {
                return $response;
            } else {
                throw new ResponseException("Payload failed with status code [{$response->getStatusCode()}] and content [{$response->getContent()}]", $response);
            }
        } catch (Exception $e) {
            throw new ResponseException($e->getMessage(), $response);
        }
    }

    private function isPayloadSuccessful(Response $response) {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }
}
