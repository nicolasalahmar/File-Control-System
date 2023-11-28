<?php

namespace App\Models;

use App\Exceptions\CreateObjectException;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GenericModel extends Model
{
    use HasFactory;

    public $validation_rules=[];

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

    public function deleteObjectDAO(): bool|string|null
    {
        self::dropCachesDAO([$this->id]);

        try{
            $res = $this->delete();
            return $res;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public static function dropCachesDAO($id_array){
        $class=get_called_class();
        foreach ($id_array as $id){
            $key = $class.$id;
            Cache::forget($key);
        }
    }

    public static function createObjectDAO($parameters){
        $class_name=get_called_class();
        $class = new $class_name();
        $validation_rules = $class->validation_rules;

        $validator = Validator::make($parameters, $validation_rules);

        if(!$validator->fails()){
            $obj = $class::create($parameters);

            return $obj;

        }else{

            throw new CreateObjectException($validator->errors()->first());
        }
    }
}
