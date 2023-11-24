<?php

namespace App\Services;

use App\Models\File;
use App\Models\Group;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class GroupService extends Service{
    public function createGroup($bodyParameters){

        $validator = Validator::make($bodyParameters, [
            'name'=>'unique:groups,name',
        ]);

        if(!$validator->fails()){
            $group = Group::create([
                'name'=>$bodyParameters['name'],
                'creator'=>auth()->user()
            ]);

            return $group;
        }else{
            return null;
        }
    }

    public function manageElementsInGroup($element_type,$op_type,$bodyParameters){
        $group = Group::getObjectDAO($bodyParameters['group_id']);
        $op = ($op_type == "attach") ? "attach": "detach";

        if($element_type=="user"){
            $ids = $bodyParameters['users_ids'][0];
            $rel_name = $group->users();
        }elseif($element_type=="file"){
            $ids = $bodyParameters['files_ids'][0];
            $rel_name = $group->files();
        }

        $ids_arr = preg_split ("/\,/", $ids);


        foreach($ids_arr as $id){
            $rel_name->$op($id);
        }
        return true;
    }
}
