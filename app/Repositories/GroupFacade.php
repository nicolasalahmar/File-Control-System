<?php

namespace App\Repositories;

class GroupFacade extends Facade
{

    CONST aspects_map = array(
        'createGroup' => array('TransactionAspect'),
        'removeGroup'=> array('TransactionAspect'),
        'addFilesToGroup'=> array('TransactionAspect'),
        'addUsersToGroup'=> array('TransactionAspect'),
        'removeFilesFromGroup'=> array('TransactionAspect'),
        'removeUsersFromGroup'=> array('TransactionAspect'),
        'myGroups'=> array('TransactionAspect'),
        'enrolledGroups'=> array('TransactionAspect'),
        'filesInGroup'=> array('TransactionAspect'),
    );
    public function __construct($message)
    {
        parent::__construct($message);
    }


    public function createGroup()
    {
            $group = $this->groupService->createGroup($this->message['bodyParameters']);
            $message = [
                    'group_id'=> $group->id,
                    'users_ids'=> [auth()->user()->id]
            ];
            $group = $this->groupService->addUsersToGroup($message);
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

        if ($check || !empty($files)){
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
