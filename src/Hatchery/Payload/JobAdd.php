<?php

namespace Hatchery\Payload;

class JobAdd extends Payload
{

    public function __construct($url, $preset, $ftpIn, $ftpOut, $options = array())
    {
        parent::__construct($url);
        $this->setPostData('preset', $preset);
        $this->setPostData('input', $ftpIn);
        $this->setPostData('output', $ftpOut);
        
        if (isset($options['transcode_options'])) {
            $this->setPostData('transcode_options', $options['transcode_options']);
        }
    }

}