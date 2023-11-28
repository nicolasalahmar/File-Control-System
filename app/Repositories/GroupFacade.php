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
        $res = $this->groupService->manageElementsInGroup("file","attach",$this->message['bodyParameters']);
        return $res;
    }
    public function addUsersToGroup(){
        $res = $this->groupService->manageElementsInGroup("user","attach",$this->message['bodyParameters']);
        return $res;
    }
    public function removeFilesFromGroup(){
        $res = $this->groupService->manageElementsInGroup("file","detach",$this->message['bodyParameters']);
        return $res;
    }
    public function removeUsersFromGroup(){
        $res = $this->groupService->manageElementsInGroup("user","detach",$this->message['bodyParameters']);
        return $res;
    }

}
