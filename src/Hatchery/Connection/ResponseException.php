<?php

namespace Hatchery\Connection;

class ResponseException extends \Exception {

    private $response;

    public function __construct($message = '', ResponseInterface $response) {
        parent::__construct($message);
        $this->response = $response;
    }
}

