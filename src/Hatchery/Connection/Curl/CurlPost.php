<?php

namespace Hatchery\Connection\Curl;

use Hatchery\Connection\TypeInterface;
use Hatchery\Payload\Payload;

class CurlPost implements TypeInterface {

    public function sendPayload(Payload $payload) {
        $ch = curl_init($payload->getUri());
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (strtolower($payload->getVerb()) === 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload->getData()));
        }

        $headers = $payload->getHeaders();
        if ( ! empty($headers)) {
            $curlHeaders = array();
            foreach ($headers as $key => $value) {
                $curlHeaders[] = $key . ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
        }

        $content = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $retHeaders = substr($content, 0, $header_size);
        $body = substr($content, $header_size);

        return new CurlResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $retHeaders, $body);
    }
}