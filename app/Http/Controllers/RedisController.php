<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function index(){
        Redis::set('user:2:first_name','Rem');
    }

    public function index2(){
        $t = Redis::get('user:1:first_name');
        dd($t);
    }

    public function index3(){
        ProcessFileJob::dispatch("0987654321");
    }
}
