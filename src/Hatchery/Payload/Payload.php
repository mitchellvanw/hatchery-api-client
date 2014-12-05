<?php

namespace Hatchery\Payload;

class Payload
{

    protected $data = array();
    protected $headers = array();
    protected $url;
    protected $method = 'post';

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setPostData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function getPostData()
    {
        return $this->data;
    }
    
    public function setMethod($method) {

        $this->method = $method;
        return $this;
    }

    public function getMethod() {

        return $this->method;
    }
    
    /**
     * Helper function to set offset and duration parameters
     * returns false when parameters are not numeric, or duration is less than or equal to 0
     * @param type $offset
     * @param type $duration
     * @return boolean
     */
    public function setOffsetAndDuration($offset, $duration) {
        if(!is_numeric($offset) || !is_numeric($duration)){
            
            return false;
        }
        
        if($duration <= 0){
            
            return false;
        }

        $transcodeOptions = array();
        
        $transcodeOptions[] = array(
            'type' => 'seek',
            'value' => $offset
        );
        $transcodeOptions[] = array(
            'type' => 'seek-offset',
            'value' => $duration
        );
        $this->setPostData('transcode_options', $transcodeOptions);
    }

}
