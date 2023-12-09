<?php

namespace App\Aspects;

use App\Models\File;

class AuthorizationAspect extends Aspect
{
    private $objMapper = [];
    public function __construct($message)
    {
        $this->objMapper = [
            "file" => "App\\Models\\File",
        ];
        parent::__construct($message);
    }

    public function before(){

        $class = new $this->objMapper[$this->message["facade"]];
        if(!empty($this->message["urlParameters"]) || !empty($this->message["bodyParameters"])){
            if(!empty($this->message["urlParameters"])){
                $obj = $class::getObjectDAO($this->message["urlParameters"]["id"]);
            }else{
                $obj = $class::getObjectDAO($this->message["bodyParameters"]["id"]);
            }


            if (!auth()->user()->can($this->message["function"], $obj)) {
                throw new \Exception("Unauthorized access");
            }
        }else{
            throw new \Exception("Unauthorized access, missing data");
        }

    }
    public function after(){

    }
    public function exception(){

    }

}
