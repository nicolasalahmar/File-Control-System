<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GenericModel extends Model
{
    use HasFactory;

    public static function getObjectDAO($id){

        $class=get_called_class();
        $key = $class.$id;

        $object = Cache::get($key);

        if($object==null) {
            $object = self::find($id);
            Cache::add($key,$object,env('CACHE_EXPIRY'));
        }
        return $object;
    }


    public function updateObjectDAO($array=[],$customCond=[]){

        $class=get_called_class();
        $id = $this->id;
        $key = $class.$id;

        Cache::forget($key);


        try{

            $res = $this->where($customCond)->update($array);

            return $res;
        }catch(Exception $e){
            return $e->getMessage();
        }


    }

}
