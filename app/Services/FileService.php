<?php

namespace App\Services;

use App\Jobs\ProcessFileJob;
use App\Models\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

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

    public function getMyFiles(){
        $files = File::where('user_id',auth()->user()->id)->get()->toArray();

        if(count($files)>0){

            return $files;
        }else{
            return null;
        }
    }

    public function uploadFiles($bodyParameters){
        if(count($bodyParameters)>0 && count($bodyParameters['files'])>0){
            $files = $bodyParameters['files'];
            foreach($files as $key=>$file){

                $storagePath = $file->store('public/documents/'.auth()->user()->name);

                $returnFiles[$key] = File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $storagePath,
                'mime_type' => $file->extension(),
                'size' => $file->getSize(),
                'checked'=>'0',
                'version'=>'0',
                'user_id'=>auth()->user()->id,
                ]);
            }

            return $returnFiles;
        }else{
            return null;
        }
    }
}
