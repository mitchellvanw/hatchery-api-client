<?php

namespace Hatchery\Connection\Curl;

use Hatchery\Connection\TypeInterface;
use Hatchery\Payload\Payload;

class CurlPost implements TypeInterface
{

    public function sendPayload(Payload $payload)
    {
        $ch = curl_init($payload->getUrl());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload->getPostData());

        $headers = $payload->getHeaders();
        if (!empty($headers)) {
            $curlHeaders = array();
            foreach ($headers as $key => $value) {
                $curlHeaders[] = $key . ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
        }

        $content = curl_exec($ch);

        return new CurlResponse(curl_getinfo($ch, CURLINFO_HTTP_CODE), $content);
    }


}