<?php

namespace App\Repositories;

use App\Services\GroupService;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class GroupFacade extends Facade
{

    public function __construct($message)
    {
        parent::__construct($message);
    }


    public function createGroup()
    {
            $group = $this->groupService->createGroup($this->message['bodyParameters']);
            return $group;
    }
    public function addFilesToGroup(){
        $res = $this->groupService->addFilesToGroup($this->message['bodyParameters']);
        return $res;
    }
    public function addUsersToGroup(){
        $res = $this->groupService->addUsersToGroup($this->message['bodyParameters']);
        return $res;
    }
    public function removeFilesFromGroup(){
        $res = $this->groupService->removeFilesFromGroup($this->message['bodyParameters']);
        return $res;
    }
    public function removeUsersFromGroup(){
        $groupFiles = $this->groupService->getGroupFiles($this->message["bodyParameters"]["group_id"]);
        $res = $this->groupService->removeUsersFromGroup($this->message['bodyParameters'],$groupFiles);
        return $res;
    }
    public function removeGroup(){
        $id = $this->message['urlParameters']['id'];
        $files = $this->groupService->getGroupFiles($id);
        $check = $this->fileService->bulkCheckIn($files->pluck('id')->toArray());
        if ($check != null){
            $res = $this->groupService->removeGroup($id);
            $this->fileService->freeFiles($files);
        }
        return $res??null;
    }
    public function myGroups(){
        return $this->groupService->userGroups(auth()->user()->id);
    }
    public function enrolledGroups(){
        return $this->groupService->groupsUserEnrolledIn(auth()->user());
    }

    public function filesInGroup(){
        $id = $this->message['urlParameters']['id'];
        return $this->groupService->getGroupFiles($id);
    }
}
