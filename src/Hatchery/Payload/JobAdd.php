<?php

namespace Hatchery\Payload;

class JobAdd implements Payload {

    private $preset;
    private $ftpIn;
    private $ftpOut;
    private $stills = [];
    private $seekOffset = 0;
    private $duration;

    public function __construct($preset, $ftpIn, $ftpOut) {
        $this->preset = $preset;
        $this->ftpIn = $ftpIn;
        $this->ftpOut = $ftpOut;
    }

    public function addOffset($seekOffset) {
        $this->seekOffset = $seekOffset;
    }

    public function addDuration($duration) {
        $this->duration = $duration;
    }

    public function addStills($directory, $filename, $amount, $format, $width, $height) {
        $this->stills = [
            'base_url' => $directory,
            'format' => $format,
            'amount' => $amount,
            'filename' => $filename,
            'width' => $width,
            'height' => $height,
        ];
    }

    public function getVerb() {
        return 'POST';
    }

    public function getUri() {
        return '/api/v2/jobs/';
    }

    public function getHeaders() {
        return ['Content-Type' => 'application/json'];
    }

    public function getData() {
        $data = [
            'input' => $this->ftpIn,
            'output' => [
                'preset' => $this->preset,
                'url' => $this->ftpOut,
                'seek_offset' => $this->seekOffset,
                'stills' => $this->stills
            ]
        ];
        if ($this->duration) {
            $data['output']['output_length'] = $this->duration;
        }
        return $data;
    }
}