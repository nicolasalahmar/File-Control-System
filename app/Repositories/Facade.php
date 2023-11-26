<?php

namespace App\Repositories;

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

    private $fileService;
    private $userService;
    private $groupService;

    private $message;
    public function __construct($message)
    {
        $this->fileService = new FileService();
        $this->userService = new UserService();
        $this->groupService = new GroupService();
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
                "success" => $instance != null,
                "data" => $instance ?? null,
                "message" => $instance != null ? $successMessage:$errorMessage,
            ];

    }

    /************
    Files
     **************/

    public function checkIn(){
        $id =  $this->message['urlParameters']['id'];
        $file = $this->fileService->checkIn($id);
        $this->message['response']=$this->response($file,"Checked In Successfully","Check In Failed");
        return $this->message;
    }

    public function getMyFiles(){
        $files = $this->fileService->getFiles();
        $this->message['response']=$this->response($files,"Files Fetched Successfully","No Files Found");

        return $this->message;
    }

    public function bulkCheckIn(){
        $id_array =  $this->message['bodyParameters'];
        $file = $this->fileService->bulkCheckIn($id_array['file_ids']);
        $this->message['response']=$this->response($file,"Checked In Successfully","Check In Failed");
        return $this->message;
    }

    public function uploadFiles(){
        $files = $this->fileService->uploadFiles($this->message['bodyParameters']);
        $this->message['response']=$this->response($files,"Files Uploaded successfully","Files Upload Failed");

        return $this->message;
    }

    public function removeFiles(){
        $id_array =  $this->message['bodyParameters'];

        $files = null;
        $res = $this->fileService->bulkCheckIn($id_array['file_ids']);
        if ($res != null)
            $files = $this->fileService->removeFiles($id_array['file_ids']);

        $this->message['response']=$this->response($files,"Files Removed successfully","Files Removal Failed");

        return $this->message;
    }

    /************
    User Auth
     **************/
    public function logIn(){
        $res = $this->userService->logIn($this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Logged in successfully","Incorrect username or password");

        return $this->message;
    }

    public function register(){
        $res = $this->userService->register($this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Created user successfully","Error creating user");

        return $this->message;
    }

    public function logOut(){
        $res = $this->userService->logOut($this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Logged out user successfully","Error logging out user");

        return $this->message;
    }

    /************
    Group
     **************/

    public function createGroup(){

        $group = $this->groupService->createGroup($this->message['bodyParameters']);
        $this->message['response']=$this->response($group,"Group created successfully","Failed To Create Group");

        return $this->message;
    }

    public function addFilesToGroup(){
        $res = $this->groupService->manageElementsInGroup("file","attach",$this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Files Added To Group","Failed To Add Files To Group");

        return $this->message;
    }
    public function addUsersToGroup(){
        $res = $this->groupService->manageElementsInGroup("user","attach",$this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Users Added To Group","Failed To Add Users To Group");

        return $this->message;
    }
    public function removeFilesFromGroup(){
        $res = $this->groupService->manageElementsInGroup("file","detach",$this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Files Removed From Group","Failed To Remove Files From Group");


        return $this->message;
    }
    public function removeUsersFromGroup(){
        $res = $this->groupService->manageElementsInGroup("user","detach",$this->message['bodyParameters']);
        $this->message['response']=$this->response($res,"Users Removed From Group","Failed To Remove Users From Group");

        return $this->message;
    }
}
