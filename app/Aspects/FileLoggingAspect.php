<?php

namespace App\Aspects;

use App\Models\File;
use App\Models\FileLog;

class FileLoggingAspect extends Aspect
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function before(){
        FileLog::create([
            "date"=>date("Y-m-d H:i:s"),
            "operation"=>$this->message["function"],
            "file_id"=>null,
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
            "status"=>"Started",
        ]);
    }
    public function after(){

        FileLog::create([
            "date"=>date("Y-m-d H:i:s"),
            "operation"=>$this->message["function"],
            "file_id"=>$this->message["response"]["data"]["0"]["id"],
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
            "status"=>"Finished",
        ]);
    }
    public function exception(){

        FileLog::create([
            "date"=>date("Y-m-d H:i:s"),
            "operation"=>$this->message["function"],
            "file_id"=>$this->message["response"]["data"]["0"]["id"],
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
            "status"=>"Exception",
        ]);
    }


}
