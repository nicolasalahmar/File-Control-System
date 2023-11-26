<?php

namespace App\Services;

use App\Exceptions\CheckInException;
use App\Exceptions\fileDeletionException;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileService extends Service
{

    public function checkIn($id)
    {

        $file = File::getObjectDAO($id);

        if ($file->checked == 0) {

            $file->updateObjectDAO([
                'checked' => 1,
                'version' => $file->version + 1
            ],
                [
                    'id' => $id,
                    'version' => $file->version,
                ]);

            return $file;
        } else {

            return null;
        }
    }

    public function getMyFiles(){
        $files = File::where('user_id',auth()->user()->id)->get()->toArray();

        if(count($files)>0){

            return $files;
        } else {
            return null;
        }
    }

    public function bulkCheckIn($id_array)
    {
        $files = [];
        DB::beginTransaction();

        try {
            foreach ($id_array as $id) {
                $file = $this->checkIn($id);
                if ($file) {
                    array_push($files, $file);
                } else {
                    throw new CheckInException('Error checking in');
                }
            }
            DB::commit();
        } catch (Exception $e) {
            $files = null;
            DB::rollBack();
        }
        return $files;
    }

    public function uploadFiles($bodyParameters)
    {
        if (count($bodyParameters) > 0 && count($bodyParameters['files']) > 0) {
            $files = $bodyParameters['files'];
            foreach ($files as $key => $file) {

                $storagePath = Storage::disk('public')->put('documents', $file);

                $returnFiles[$key] = File::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $storagePath,
                    'mime_type' => $file->extension(),
                    'size' => $file->getSize(),
                    'checked' => '0',
                    'version' => '0',
                    'user_id' => auth()->user()->id,
                ]);
            }

            return $returnFiles;
        } else {
            return null;
        }
    }

    public function removeFiles($id_array)
    {
        $all_deleted = [];
        $arr = [];

        if (count($id_array) > 0) {

            foreach ($id_array as $file_id) {
                $file = File::getObjectDAO($file_id);

                array_push($arr, $file);

                if ($file) {
                    $res = $file->deleteFileFSDAO();
                    $file->deleteObjectDAO();
                    array_push($all_deleted, $res);
                }
            }
            if (in_array(false, $all_deleted)) {
                throw new fileDeletionException('Unable to delete file');
            } else {
                return 1;
            }
        } else {
            return null;
        }
    }
}
