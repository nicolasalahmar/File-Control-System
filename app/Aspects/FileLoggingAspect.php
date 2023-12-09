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
            "upload_date"=>$this->message["function"]=="uploadFiles" ? date("Y-m-d H:i:s") : null,
            "check_in_date"=>$this->message["function"]=="checkIn" ? date("Y-m-d H:i:s") : null,
            "modify_date"=>$this->message["function"]=="checkOut" ? date("Y-m-d H:i:s") : null,
            "check_out_date"=>$this->message["function"]=="checkOut" ? date("Y-m-d H:i:s") : null,
            "file_id"=>$this->getFileId(),
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
            "status"=>"Started",
        ]);
    }
    public function after(){
        FileLog::create([
            "upload_date"=>$this->message["function"]=="uploadFiles" ? date("Y-m-d H:i:s") : null,
            "check_in_date"=>$this->message["function"]=="checkIn" ? date("Y-m-d H:i:s") : null,
            "modify_date"=>$this->message["function"]=="checkOut" ? date("Y-m-d H:i:s") : null,
            "check_out_date"=>$this->message["function"]=="checkOut" ? date("Y-m-d H:i:s") : null,
            "file_id"=>$this->getFileId(),
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
            "status"=>"Finished",
        ]);
    }
    public function exception(){
        FileLog::create([
            "upload_date"=>$this->message["function"]=="uploadFiles" ? date("Y-m-d H:i:s") : null,
            "check_in_date"=>$this->message["function"]=="checkIn" ? date("Y-m-d H:i:s") : null,
            "modify_date"=>$this->message["function"]=="checkOut" ? date("Y-m-d H:i:s") : null,
            "check_out_date"=>$this->message["function"]=="checkOut" ? date("Y-m-d H:i:s") : null,
            "file_id"=>$this->getFileId(),
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
            "status"=>"Exception",
        ]);
    }

    public function getFileId(){
        if(!empty($this->message["urlParameters"]) || !empty($this->message["bodyParameters"])) {
            if (!empty($this->message["urlParameters"]) && isset($this->message["urlParameters"]["id"])) {
                return $this->message["urlParameters"]["id"];
            } elseif(!empty($this->message["bodyParameters"]) && isset($this->message["bodyParameters"]["id"])) {
                return $this->message["bodyParameters"]["id"];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

}
