<?php

namespace Hatchery\Connection\Curl;

use Hatchery\Connection\TypeInterface;
use Hatchery\Payload\Payload;

class CurlPost implements TypeInterface {

    private $baseUrl;
    private $apiKey;

    public function __construct($baseUrl, $apiKey) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    public function sendPayload(Payload $payload) {
        $url = $this->generateUrl($payload->getUri());
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (strtolower($payload->getVerb()) === 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload->getData()));
        }

        $headers = $this->getCurlHeaders($payload->getHeaders());
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $content = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $retHeaders = substr($content, 0, $header_size);
        $body = substr($content, $header_size);

        return new CurlResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $retHeaders, $body);
    }

    private function generateUrl($uri) {
        return rtrim("{$this->baseUrl}/$uri", '/');
    }

    private function getCurlHeaders(array $payloadHeaders) {
        $defaultHeaders = array('x-auth-token' => $this->apiKey);
        $headers = array_merge($payloadHeaders, $defaultHeaders);
        foreach ($headers as $key => $value) {
            $curlHeaders[] = "{$key}: {$value}";
        }
        return $curlHeaders;
    }
}