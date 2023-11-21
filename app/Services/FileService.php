<?php

namespace App\Services;

use App\Jobs\ProcessFileJob;
use App\Models\File;
use Illuminate\Support\Facades\Redis;

class FileService extends Service{
    public function checkIn($message){
        $id = $message['urlParameters']['id'];

        $lockKey = "file_lock:$id";
        $redisFileStatus = Redis::get($lockKey);

        if($redisFileStatus == null){
            ProcessFileJob::dispatch($id);

            $file = File::where('id',$id)->first();
            $file->update([
                'checked'=>1
            ]);

            $message['response']=[
                "success" => true,
                "data" => $file,
                "message" => "Checked In Successfully",
            ];

            return $message;
        }else{

            $message['response']=[
                "success" => false,
                "data" =>[],
                "message" => "Check In Failed",
            ];

            return $message;

        }
    }

    public function getFiles(){
        $files = File::get()->toArray();
        if(count($files)>0){
            return $this->response(true,$files,"Files Fetched Successfully");
        }else{
            return $this->response(false,[],"No Files Found");
        }
    }
}
