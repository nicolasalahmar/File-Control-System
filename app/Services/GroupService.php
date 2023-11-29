<?php

namespace App\Services;

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
