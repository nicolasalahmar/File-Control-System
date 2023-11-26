<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class File extends GenericModel
{
    use HasFactory;

    public $fillable = [
      'name',
      'path',
      'mime_type',
      'size',
      'checked',
      'version',
      'user_id',
    ];

    public $hidden = ['path'];

    public function deleteFileFSDAO(){
        return Storage::disk('public')->delete($this->path);
    }

}
