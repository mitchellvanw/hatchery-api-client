<?php

namespace Hatchery\Payload;

class JobStatus implements Payload {

    private $identifier;

    public function __construct($identifier) {
        $this->identifier = $identifier;
    }

    public function getVerb() {
        return 'get';
    }

    public function getUri() {
        return "/{$this->identifier}";
    }

    public function getHeaders() {
        return ['Content-Type' => 'application/json'];
    }

    public function getData() {
        return array();
    }
}