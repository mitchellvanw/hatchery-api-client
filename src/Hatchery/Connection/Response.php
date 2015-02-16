<?php

namespace Hatchery\Connection;

interface Response {

    public function getStatusCode();
    public function getContent();
    public function getJsonResponse();
    public function getLocation();
}