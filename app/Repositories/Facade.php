<?php

namespace App\Repositories;

use App\Exceptions\CreateObjectException;
use App\Services\FileService;
use App\Services\UserService;
use App\Services\GroupService;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


//use Your Model

/**
 * Class Facade.
 */
class Facade extends BaseRepository
{

    protected $message;

    private $facadeMapper = [];
    protected $userService;
    protected $fileService;
    protected $groupService;
    protected $aspects_map = [];

    public function __construct($message)
    {
        $this->userService = new UserService();
        $this->fileService = new FileService();
        $this->groupService = new GroupService();

        $this->facadeMapper = [
            "user"=>"App\\Repositories\\UserFacade",
            "file"=>"App\\Repositories\\FileFacade",
            "group"=>"App\\Repositories\\GroupFacade",
        ];

        $this->message = $message;
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\GenericModel::class;
    }

    public function response($instance,$successMessage,$errorMessage)
    {

        return $this->message['response']=
            [
                "success" => $instance != null  ,
                "data" => $instance ?? null,
                "message" => $instance != null ? $successMessage:$errorMessage,
            ];

    }

    public function exceptionResponse($errorMessage)
    {

        return $this->message['response']=
            [
                "success" => false ,
                "message" => $errorMessage,
            ];

    }

/*    public function handleException(\Closure $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            $this->message["response"]=$this->exceptionResponse($e->getMessage());
            return $this->message;
        }
    }*/


    public function execute(){
        $facade = $this->message["facade"];
        $func = $this->message["function"];
        $facadeClass= new $this->facadeMapper[$facade]($this->message);
        try{

            $this->executeBefore($func, $facadeClass);

            $result = $facadeClass->$func();
            $this->message["response"]=$this->response($result,__("api.".$facade.".".$func.".success"),__("api.".$facade.".".$func.".failure"));

            $this->executeAfter($func, $facadeClass);

            return $this->message;
        }catch(\Exception $e){
            $this->executeException($func,$facadeClass);
            $this->message["response"]=$this->exceptionResponse($e->getMessage());
            return $this->message;
        }
    }

    public function executeBefore($func,$facadeClass){
        $aspects = $facadeClass::aspects_map[$func];

        foreach ($aspects as $aspect){
            $obj = "App\\Aspects\\".$aspect;
            $class = new $obj($this->message);
            $class->before();
        }

    }
    public function executeAfter($func,$facadeClass){

        $aspects = $facadeClass::aspects_map[$func];
        foreach ($aspects as $aspect){
            $obj = "App\\Aspects\\".$aspect;
            $class = new $obj($this->message);
            $class->after();
        }

    }

    public function executeException($func,$facadeClass){

        $aspects = $facadeClass::aspects_map[$func];
        foreach ($aspects as $aspect){
            $obj = "App\\Aspects\\".$aspect;
            $class = new $obj($this->message);
            $class->exception();
        }

    }
}
