<?php

namespace Hatchery\Connection;

class ResponseException extends \Exception {

    private $response;

    public function __construct($message = '', Response $response) {
        parent::__construct($message);
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }
}

