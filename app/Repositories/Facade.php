<?php

namespace App\Repositories;

use App\Jobs\ProcessFileJob;
use App\Models\File;
use App\Services\FileService;
use App\Services\UserService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class Facade.
 */
class Facade extends BaseRepository
{

    private $fileService;
    private $userService;
    public function __construct()
    {
        $this->fileService = new FileService();
        $this->userService = new UserService();
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\GenericModel::class;
    }

    public function checkIn($message){
        $id =  $message['urlParameters']['id'];

        $file = $this->fileService->checkIn($id);

        $message['response']=[
            "success" => $file != null,
            "data" => $file ?? null,
            "message" => $file != null ? "Checked In Successfully":"Check In Failed",
        ];

        return $message;
    }

    public function logIn($message){
        $res = $this->userService->logIn($message['bodyParameters']);
        $message['response']=[
            "success" => $res != null,
            "data" => $res ?? null,
            "message" => $res != null ? "Logged in successfully":"Incorrect username or password",
        ];
        return $message;
    }

    public function register($message){
        $res = $this->userService->register($message['bodyParameters']);
        $message['response']=[
            "success" => $res != null,
            "data" => $res ?? null,
            "message" => $res != null ? "Created user successfully":"Error creating user",
        ];
        return $message;
    }

    public function logOut($message){
        $res = $this->userService->logOut($message['bodyParameters']);
        $message['response']=[
            "success" => $res != null,
            "data" => $res ?? null,
            "message" => $res != null ? "Logged out user successfully":"Error logging out user",
        ];
        return $message;
    }
}
