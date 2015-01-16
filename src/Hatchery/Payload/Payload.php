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

    public function setMethod($method)
    {

        $this->method = $method;
        return $this;
    }

    public function getMethod()
    {

        return $this->method;
    }

    /**
     * Helper function to set offset and duration parameters
     * returns false when parameters are not numeric, or duration is less than or equal to 0
     * @param float $offset
     * @param float $duration
     * @return boolean
     */
    public function setOffsetAndDuration($offset, $duration)
    {
        if (!is_numeric($offset) || !is_numeric($duration)) {

            return false;
        }

        if ($duration <= 0) {

            return false;
        }

        $postData = $this->getPostData();

        $options= array();
        $options[] = array('name' => 'video-transcode.output-video.duration', 'value' => $duration);
        $options[] = array('name' => 'video-transcode.input-video.video-seek-offset', 'value' => 'offset-seconds');
        $options[] = array('name' => 'video-transcode.input-video.video-seek-offset[offset-seconds].seek-offset-seconds', 'value' => $offset);

        $job = $postData['job'];
        foreach ($job['tasks'] as &$task) {
            if ($task['type'] === 'transcode') {
                foreach ($task['actions'] as &$action) {
                    if ($action['actionType'] === 'video-transcode') {
                        $action['options'] = $options;
                    }
                }
            }
        }

        $this->setPostData('job', $job);

        return true;
    }

    /**
     * @param $filename
     * @param $duration
     * @param $stills
     * @return array
     */
    public function addStills($filename, $duration, $stills)
    {
        $postData = $this->getPostData();
        $files = $postData['files'];
        $job = $postData['job'];
        $fileNames = array();

        for ($i = 0; $i < $stills; $i++) {

            $stillFilename = str_replace('.' . pathinfo(parse_url($filename, PHP_URL_PATH), PATHINFO_EXTENSION), '_' . $i . '.jpg', $filename);
            $fileNames[] = $stillFilename;
            $publishTask = array();

            //create publish file ref
            $publishFileRef = JobAdd::uuid();
            //create publish ta sk ref
            $publishTaskRef = JobAdd::uuid();
            //add output file
            $files[] = array('id' => $publishFileRef, 'url' => $stillFilename);
            //add task
            $publishTask['id'] = $publishTaskRef;
            $publishTask['type'] = 'publish';
            //add file reference
            $file['ref'] = $publishFileRef;
            $publishTask['file'] = $file;

            $action= array();
            $action['actionType'] = 'generate-still';
            $action['preset'] = 'Default Still Preset';
            $action['options'][] = array('name' => 'generate-still.input-video.still-seek-offset', 'value' => 'percentage');
            $action['options'][] = array('name' => 'generate-still.input-video.still-seek-offset[percentage].percentage', 'value' => 10 + ($i * 20));

            $filesRequirements= array();
            $filesRequirements[] = array('file_requirement_id' => 3, 'ref' => $files[0]['id']);
            $filesRequirements[] = array('file_requirement_id' => 4, 'ref' => $publishFileRef);

            $action['files'] = $filesRequirements;

            foreach ($job['tasks'] as &$task) {
                if ($task['type'] === 'transcode') {
                    $task['actions'][] = $action;
                }
            }

            //add task to tasks
            $job['tasks'][] = $publishTask;
        }

        $this->setPostData('files', $files);
        $this->setPostData('job', $job);

        return $fileNames;
    }

}
