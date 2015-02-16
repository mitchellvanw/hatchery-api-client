<?php

namespace Hatchery\Payload;

interface Payload {

    public function getVerb();
    public function getUri();
    public function getHeaders();
    public function getData();
}