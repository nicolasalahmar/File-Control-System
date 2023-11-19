<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class Facade.
 */
class Facade extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return \App\Models\GenericModel::class;
    }

    public function callService($message){
        $servicesDirectory = app()->path('Services');

        if(isset($servicesDirectory) && $servicesDirectory != ""){
            $serviceFiles = scandir($servicesDirectory);

            foreach ($serviceFiles as $serviceFile) {
                if (strpos($serviceFile, '.php') !== false) {
                    $serviceClassName = str_replace('.php', '', $serviceFile);
                    $serviceClass = app()->make("App\\Services\\{$serviceClassName}");

                    if (method_exists($serviceClass, $message['function'])) {
                        $result = call_user_func([$serviceClass, $message['function']], $message);
                        return $result;
                    }
                }
            }
        }

    }

}
