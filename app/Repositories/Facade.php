<?php

namespace App\Repositories;

use App\Jobs\ProcessFileJob;
use App\Models\File;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Support\Facades\Redis;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class Facade.
 */
class Facade extends BaseRepository
{

    public function __construct()
    {
        $fileService = new FileService();
        $userServce = new UserService();
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\GenericModel::class;
    }

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

            return $response(true,$file,"Checked In Successfully");
        }else{
            return $this->response(false,[],"Check In Failed");
        }
    }
}
