<?php

namespace Hatchery\Payload;

class JobAdd extends Payload
{

    public function __construct($url, $preset, $ftpIn, $ftpOut, $stills = 0, $duration = 0)
    {
        parent::__construct($url);

        $files = [];
        $tasks = [];

        //create acquire
        $acquireFileRef = $this->uuid();
        $acquireTaskRef = $this->uuid();
        //add input file
        $files[] = ['id' => $acquireFileRef, 'url' => $ftpIn];
        //add task
        $acquireTask = Object();
        $acquireTask->id = $acquireTaskRef;
        $acquireTask->type = 'acquire';
        $file = Object();
        $file->ref = $acquireTaskRef;
        $acquireTask->file = $file;

        $tasks[] = $acquireTask;


        $transcodeTaskRef = $this->uuid();
        $transcodeTask = Object();
        $transcodeTask->id = $transcodeTaskRef;
        $transcodeTask->type = 'transcode';
        $transcodeTask->depends_on = $acquireTaskRef;

        $actions =  [];
        $action = Object();
        $action->actionType  = 'video-transcode';
        $action->preset = $preset;
        $action->options = [];

        $files = [];
        $file = Object();
        $file->file_requirement_id = 2;
        $file->ref = $acquireFileRef;


        //create transcode






        $publishFileRef = $this->uuid();
        $publishTaskRef = $this->uuid();
        //add output file
        $files[] = ['id' => $publishFileRef, 'url' => $ftpOut];
        $publishTask = Object();
        $publishTask->id = $publishTaskRef;
        $acquireTask->type = 'publish';
        $file = Object();
        $file->ref = $acquireTaskRef;
        $acquireTask->file = $file;



        $job = Object();


        for($i = 0; i < $stills; $i++){

            $stillOffset = $duration / $stills + 2;

            $files[] = ['id' => $this->uuid(), 'url' => $this->replace_extension($ftpOut, 'png')];


        }

        $job->tasks = $tasks;

        $this->setPostData('preset', $preset);
        $this->setPostData('input', $ftpIn);
        $this->setPostData('output', $ftpOut);
    }

    function replace_extension($filename, $new_extension) {
        return preg_replace('/\..+$/', '.' . $new_extension, $filename);
    }

    function uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }



}