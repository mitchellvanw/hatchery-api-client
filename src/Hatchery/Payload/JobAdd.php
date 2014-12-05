<?php

namespace Hatchery\Payload;

class JobAdd extends Payload
{

    public function __construct($url, $preset, $ftpIn, $ftpOut)
    {
        parent::__construct($url);
        $this->setPostData('preset', $preset);
        $this->setPostData('input', $ftpIn);
        $this->setPostData('output', $ftpOut);
    }

}