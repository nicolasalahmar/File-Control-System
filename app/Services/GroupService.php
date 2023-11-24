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
}
