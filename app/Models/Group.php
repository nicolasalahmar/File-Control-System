<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends GenericModel
{
    use HasFactory;

    public $fillable = [
        'name',
    ];
    public function files()
    {
        return $this->belongsToMany(File::class, 'group_files','file_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users','user_id');
    }
}
