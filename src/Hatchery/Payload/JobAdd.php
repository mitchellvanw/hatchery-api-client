<?php

namespace Hatchery\Payload;

class JobAdd extends Payload
{

    public function __construct($url, $preset, $ftpIn, $ftpOut)
    {
        parent::__construct($url);

        $output = array();
        $output['url'] = $ftpOut;
        $output['preset'] = $preset;


        $this->data['input'] = $ftpIn;
        $this->data['output'] = $output;
    }

    public function addOffset($offset)
    {
        $this->data['output']['seek_offset'] = $offset;
    }

    public function addDuration($duration)
    {
        $this->data['output']['output_length'] = $duration;
    }

    public function addStills($directory, $filename, $amount, $format, $width, $height)
    {
        $stills = array();
        $stills['base_url'] = $directory;
        $stills['format'] = $format;
        $stills['amount'] = $amount;
        $stills['filename'] = $filename;

        $this->data['output']['stills'] = $stills;
    }

}