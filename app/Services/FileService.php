<?php

namespace App\Services;

use App\Jobs\ProcessFileJob;
use App\Models\File;
use Illuminate\Support\Facades\Redis;

class FileService extends Service{

    public function checkIn($id){

        $file = File::getObjectDAO($id);

        if($file->checked == 0){

            $file->updateObjectDAO([
                'checked'=>1,
                'version'=>$file->version+1
            ],
                [
                    'id'=>$id,
                    'version'=>$file->version,
                ]);

            return $file;
        }else{

            return null;
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
