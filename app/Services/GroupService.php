<?php

namespace App\Services;

use App\Models\File;
use App\Models\Group;

class GroupService extends Service{
    public function createGroup($bodyParameters){

        $parameters = [
            'name'=>$bodyParameters['name'],
            'creator_id'=>auth()->user()->id
        ];

        $group = Group::createObjectDAO($parameters);

        return $group;

    }

    public function checkFilesOwnership($ids_arr){
        foreach($ids_arr as $id){
            $file = File::getObjectDAO($id);
            if($file->user_id != auth()->user()->id){
                return false;
            }
        }
        return true;
    }
    public function addFilesToGroup($bodyParameters){
        $group = Group::getObjectDAO($bodyParameters['group_id']);
        $ids = $bodyParameters['files_ids'][0];
        $ids_arr = preg_split ("/\,/", $ids);

        if($this->checkFilesOwnership($ids_arr)){
            foreach($ids_arr as $id){
                $group->files()->attach($id);
            }
            return true;
        }else{
            return null;
        }

    }
    public function addUsersToGroup($bodyParameters){
        $group = Group::getObjectDAO($bodyParameters['group_id']);
        $ids = $bodyParameters['users_ids'][0];
        $ids_arr = preg_split ("/\,/", $ids);

        foreach($ids_arr as $id){
            $group->users()->attach($id);
        }
        return true;
    }
    public function removeFilesFromGroup($bodyParameters){
        $group = Group::getObjectDAO($bodyParameters['group_id']);
        $ids = $bodyParameters['files_ids'][0];
        $ids_arr = preg_split ("/\,/", $ids);

        if($this->checkFilesOwnership($ids_arr)){
            foreach($ids_arr as $id){
                $group->files()->detach($id);
            }
            return true;
        }else{
            return null;
        }
    }
    public function removeUsersFromGroup($bodyParameters,$groupFiles){
        $group = Group::getObjectDAO($bodyParameters['group_id']);

        $ids = $bodyParameters['users_ids'][0];
        $ids_arr = preg_split ("/\,/", $ids);

        if(!$this->checkUsersCheckedFilesInGroup($groupFiles,$ids_arr)){
            foreach($ids_arr as $id){
                $group->users()->detach($id);
            }
            return true;
        }else{
            return false;
        }

    }

    public function checkUsersCheckedFilesInGroup($groupFiles,$ids_arr){

        if(!empty($groupFiles)){
            foreach($groupFiles as $file){
                if(in_array($file->file_holder_id,$ids_arr)){
                    return true;
                }
            }
        }else{
            return false;
        }

    }

    public function getGroupFiles($id){
        $group = Group::getObjectDAO($id);
        return $group->files()->get();
    }

    public function removeGroup($id){
        $group = Group::find($id);
        return $group->deleteObjectDAO();
    }

    public function MyGroups($id){
        return Group::where('creator_id', $id)->get();
    }
}
