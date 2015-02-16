<?php namespace Hatchery\Payload;

final class Stills {

    private $directory;
    private $filename;
    private $amount;
    private $format;
    private $width;
    private $height;

    public function __construct($directory, $filename, $amount, $format, $width, $height) {
        $this->directory = $directory;
        $this->filename = $filename;
        $this->amount = $amount;
        $this->format = $format;
        $this->width = $width;
        $this->height = $height;
    }

    public function getDirectory() {
        return $this->directory;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getFormat() {
        return $this->format;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }
}