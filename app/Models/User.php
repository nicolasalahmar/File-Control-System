<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Exceptions\CreateObjectException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guard = 'web';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public $validation_rules = [
        'name' => 'required|string',
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string'
    ];

    private $userPermissions = [
        'user-view-file-log',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_users','user_id')->withTimestamps();
    }

    public static function createObjectDAO($parameters){
        $class_name=get_called_class();
        $class = new $class_name();
        $validation_rules = $class->validation_rules;

        $validator = Validator::make($parameters, $validation_rules);

        if(!$validator->fails()){
            $obj = $class::create($parameters);

            $obj->syncPermissions($class->userPermissions);
            $obj->assignRole('User');

            return $obj;
        }else{
            throw new CreateObjectException($validator->errors()->first());
        }
    }
}
