<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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



}
