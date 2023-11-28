<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService extends Service{

    public function logIn($bodyParameters)
    {
        $data = Validator::make($bodyParameters, [
            'email' => 'required|email',
            'password' => 'required|string'
        ])->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {

            return null;
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return $res;
    }
    public function logOut()
    {
        $res = auth()->user()->tokens()->delete();
        return true;
    }
    public function register($bodyParameters)
    {

        $parameters = [
            'name' => $bodyParameters['name'],
            'email' => $bodyParameters['email'],
            'password' => bcrypt($bodyParameters['password'])
        ];

        $user = User::createObjectDAO($parameters);

        return $user;
    }
}
