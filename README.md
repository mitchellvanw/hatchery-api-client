DESCRIPTION
===========
This is the API client for Hatchery Video Transcoding API. Use this to simplify the use of the API in PHP.

Example
=======

    <?php

    include '../src/Hatchery/Autoloader.php';
    $client  = new Hatchery\Client('api_url', 'api_key');
    $payload = $client->createJobAddPayload('preset', 'ftp-in', 'ftp-out');
    $client->sendPayload($payload);
