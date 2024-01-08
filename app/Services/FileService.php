<?php

namespace App\Services;

use App\Exceptions\CheckInException;
use App\Exceptions\fileDeletionException;
use App\Exceptions\FileInUseException;
use App\Models\File;
use App\Models\Group;
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
                'version' => $file->version + 1,
                'file_holder_id' => auth()->user()->id
            ],
                [
                    'id' => $id,
                    'version' => $file->version,
                ]);

            return $file;
        } else {
            throw new FileInUseException('File is in use by someone else');
        }
    }


    public function bulkCheckIn($id_array)
    {
        $files = [];

            $ids_arr = preg_split ("/\,/", $id_array);

            foreach ($ids_arr as $id) {
                $file = $this->checkIn($id);
                array_push($files, $file);
            }

        return $files;
    }

    public function checkOut($bodyParameters)
    {
        $id = $bodyParameters["id"];
        $file = File::getObjectDAO($id);

        if (isset($file) && $file->checked == 1 ) {
            $newFile = $bodyParameters['file'];
            $storagePath = Storage::disk('public')->put('documents', $newFile);
            $file->deleteFileFSDAO();

            $file->updateObjectDAO([
                'checked' => 0,
                'version' => 0,
                'path' => $storagePath,
                'file_holder_id' => null
            ],
                [
                    'id' => $id,
                ]);
            return $file;
        } else {
            return null;
        }
    }

    public function freeFiles($files)
    {
        $res = [];
        foreach ($files as $file) {
            $r = $file->updateObjectDAO(['checked' => 0, 'version' => 0], []);
            array_push($res, $r);
        }
        return !in_array(null, $res);
    }

    public function getMyFiles()
    {
        $files = File::where('user_id', auth()->user()->id)->get()->toArray();   //todo this should be DAO
        if (count($files) > 0) {
            return $files;
        } else {
            return [];
        }
    }

    public function uploadFiles($bodyParameters)
    {
        if (count($bodyParameters) > 0 && count($bodyParameters['files']) > 0) {
            $files = $bodyParameters['files'];
            foreach ($files as $key => $file) {

                $storagePath = Storage::disk('public')->put('documents', $file);    //todo this should be DAO

                $parameters = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $storagePath,
                    'mime_type' => $file->extension(),
                    'size' => $file->getSize(),
                    'checked' => '0',
                    'version' => '0',
                    'user_id' => auth()->user()->id,
                ];

                $returnFiles[$key] = File::createObjectDAO($parameters);
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
        $id_array = explode(',',$id_array);
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

    public function readFile($id)
    {
        $file = File::getObjectDAO($id);
        return $file;
    }

}
