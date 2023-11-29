<?php

namespace App\Models;

use App\Exceptions\CreateObjectException;
use App\Exceptions\ObjectNotFoundException;
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
            if ($object == null){
                throw new ObjectNotFoundException($class.' object not found');
            }else{
                Cache::add($key,$object,env('CACHE_EXPIRY'));
            }
        }
        return $object;
    }


    public function updateObjectDAO($array=[],$customCond=[]){

        $class=get_called_class();
        $id = $this->id;
        $key = $class.$id;

        Cache::forget($key);

        $res = $this->where($customCond + ['id'=>$this->id])->update($array);
        if ($res < 1){
            throw new ObjectNotFoundException($class.' object not found');
        }
        return $res;
    }

    public function deleteObjectDAO()
    {
        self::dropCachesDAO([$this->id]);

        $res = $this->delete();

        if ($res < 1){
            throw new ObjectNotFoundException(get_called_class().' object not found');
        }
        return $res;
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
