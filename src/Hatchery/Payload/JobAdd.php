<?php

namespace Hatchery\Payload;

class JobAdd extends Payload
{

    public function __construct($url, $preset, $ftpIn, $ftpOut)
    {
        parent::__construct($url);

        $files = [];
        $tasks = [];

        //create acquire file ref
        $acquireFileRef = $this->uuid();
        //create acquire task ref
        $acquireTaskRef = $this->uuid();
        //add input file
        $files[] = ['id' => $acquireFileRef, 'url' => $ftpIn];
        //add task
        $acquireTask['id'] = $acquireTaskRef;
        $acquireTask['type'] = 'acquire';
        //add file reference
        $file['ref'] = $acquireFileRef;
        $acquireTask['file']  = $file;
        //add task to tasks
        $tasks[] = $acquireTask;

        //create publish file ref
        $publishFileRef = $this->uuid();
        //create publish task ref
        $publishTaskRef = $this->uuid();
        //add output file
        $files[] = ['id' => $publishFileRef, 'url' => $ftpOut];
        //add task
        $publishTask['id'] = $publishTaskRef;
        $publishTask['type'] = 'publish';
        //add file reference
        $file['ref'] = $publishFileRef;
        $publishTask['file'] = $file;

        $transcodeTaskRef = $this->uuid();
        $transcodeTask['id'] = $transcodeTaskRef;
        $transcodeTask['type'] = 'transcode';
        $transcodeTask['depends_on'][] = $acquireTaskRef;

        $action['actionType']  = 'video-transcode';
        $action['preset'] = $preset;
        $action['options'] = [];

        $filesRequirements = [];
        $filesRequirements[] = ['file_requirement_id' => 2, 'ref' => $acquireFileRef];
        $filesRequirements[] = ['file_requirement_id' => 1, 'ref' => $publishFileRef];

        $action['files'] = $filesRequirements;

        $transcodeTask['actions'][] = $action;
        $publishTask['depends_on'][] = $transcodeTaskRef;

        $tasks[] = $publishTask;
        $tasks[] = $transcodeTask;
        $job = [];
        $job['tasks'] = $tasks;


        $this->setPostData('job', $job);
        $this->setPostData('files', $files);
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