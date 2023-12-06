<?php

namespace App\Services;

use App\Exceptions\ObjectNotFoundException;
use App\Models\Log;

class LogService extends Service
{

    /**
     * @throws ObjectNotFoundException
     */
    public function exportReport($message){
        $logCondition = $message['logCondition'] ?? [];
        return Log::getMultipleObjectsConditionDAO($logCondition);
    }

}
