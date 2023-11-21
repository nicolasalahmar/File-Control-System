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
            if(count($bodyParamaters)>0){
                $message['bodyParameters']=[];
                foreach($bodyParamaters as $key=>$value){
                    $message['bodyParameters'][$key]=$value;
                }
            }
        }

        $facade = new Facade();
        $result = $facade->callService($message);

        return response()->json($result['response']);
    }

}
