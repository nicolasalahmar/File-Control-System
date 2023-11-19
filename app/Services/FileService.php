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

            return $this->response(true,$file,"Checked In Successfully");
        }else{
            return $this->response(false,[],"Check In Failed");
        }
    }
}
