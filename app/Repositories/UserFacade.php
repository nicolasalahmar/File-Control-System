<?php

namespace App\Repositories;

use App\Services\UserService;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class UserFacade extends Facade
{

    public function __construct($message)
    {
        parent::__construct($message);
    }


    public function logIn(){
        $res = $this->userService->logIn($this->message['bodyParameters']);
        return $res;
    }

    public function register(){
        $res = $this->userService->register($this->message['bodyParameters']);
        return $res;
    }

    public function logOut(){
        $res = $this->userService->logOut($this->message['bodyParameters']);
        return $res;
    }

}
