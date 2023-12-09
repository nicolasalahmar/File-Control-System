<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function readFile(User $user, File $file): bool
    {
        return $user->groups->intersect($file->groups)->isNotEmpty();
    }


}
