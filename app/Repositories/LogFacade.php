<?php

namespace App\Repositories;

use App\Exceptions\ObjectNotFoundException;

class LogFacade extends Facade
{
    const aspects_map = array(
        'UserReports' => array('TransactionAspect', 'LoggingAspect'),
        'FileReports' => array('TransactionAspect', 'LoggingAspect'),
    );

    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function UserReports()
    {
        $id = $this->message['urlParameters']['id'];
        $this->message['logCondition'] = [['user_id', '=', $id], ['operation', '=', 'checkIn']];
        return $this->logService->exportReport($this->message);
    }

//    public function FileReports()
//    {
//        $id = $this->message['urlParameters']['id'];
//        $this->message['logCondition'] = [['file', '=', $id], ['operation', '=', 'checkIn']];
//        return $this->logService->exportReport($this->message);
//    }
}
