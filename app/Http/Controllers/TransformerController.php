<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $message = $this->getRouteExploded($message,$routeName);

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

        $facade = new Facade($message);
        $result = $facade->execute();

        return response()->json($result['response'], isset($result["response"]["statusCode"]) && $result["response"]["statusCode"] != null ? $result["response"]["statusCode"] : 200);
    }

    public function getRouteExploded($message,$routeName){

            $exp_arr=explode(".",$routeName);
            if(isset($exp_arr) && count($exp_arr)==2){
                $message["facade"]=$exp_arr[0];
                $message["function"]=$exp_arr[1];
                return $message;
            }else{
                return null;
            }


    }

}
