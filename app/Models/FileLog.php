<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLog extends Model
{
    use HasFactory;

    public  $fillable=[
        "upload_date",
        "check_in_date",
        "modify_date",
        "check_out_date",
        "file_id",
        "user_id",
        "status"
    ];
}
