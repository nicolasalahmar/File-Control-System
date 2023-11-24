<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Repositories\Facade;

class TransformerController extends Controller
{
    public function transform(Request $request){
        $currentRoute = Route::current();
        $routeName = $currentRoute->getName();

        $method = $request->method();

        $urlParameters = $currentRoute->parameters();

        $message = [];

        $message['function']=$routeName;
        $message['method']=$method;
        $message['request']=$request;

        if(count($urlParameters) >0){
            $message['urlParameters']=[];
            foreach($urlParameters as $key=>$value){
                $message['urlParameters'][$key]=$value;
            }
        }

        if(isset($method) && $method=="POST"){
            $bodyParamaters = $request->all();
            $message['bodyParameters']=[];
            if(count($bodyParamaters)>0){
                foreach($bodyParamaters as $key=>$value){
                    $message['bodyParameters'][$key]=$value;
                }
            }
        }

        $func = $message['function'];
        $facade = new Facade($message);
        $result = $facade->$func($message);

        return response()->json($result['response']);
    }

}
