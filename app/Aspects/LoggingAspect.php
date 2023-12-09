<?php

namespace App\Aspects;

use App\Models\Log;

class LoggingAspect extends Aspect
{


    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function before(){
        Log::create([
            "operation"=>$this->message["function"],
            "username"=>auth()->user() != null ? auth()->user()->name : null,
            "status"=>"Started",
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
        ]);
    }
    public function after(){
        Log::create([
            "operation"=>$this->message["function"],
            "username"=>auth()->user() != null ? auth()->user()->name : null,
            "status"=>"Finished",
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
        ]);
    }
    public function exception(){
        Log::create([
            "operation"=>$this->message["function"],
            "username"=>auth()->user() != null ? auth()->user()->name : null,
            "status"=>"Exception",
            "user_id"=>auth()->user() != null ? auth()->user()->id : null,
        ]);
    }

}
