<?php

namespace App\Services;

use App\Jobs\ProcessFileJob;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class UserService extends Service{

    public function logIn($message): \Illuminate\Http\JsonResponse
    {
        $data = Validator::make($message['bodyParameters'], [
            'email' => 'required|email',
            'password' => 'required|string'
        ])->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $message['response']=[
                "success" => false,
                "data" => $user,
                "message" => "Incorrect username or password",
            ];

            return $message;


        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        $message['response']=[
            "success" => true,
            "data" => $res,
            "message" => "Created user successfully",
        ];

        return $message;

    }
    public function logOut($message): \Illuminate\Http\JsonResponse
    {
        auth()->user()->tokens()->delete();
        $res = [
            'message' => 'user logged out'
        ];

        $message['response']=[
            "success" => true,
            "data" => $res,
            "message" => "Logged out user successfully",
        ];

        return $message;

    }
    public function register($message): \Illuminate\Http\JsonResponse
    {
        $data = Validator::make($message['bodyParameters'], [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string'
        ])->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $message['response']=[
            "success" => true,
            "data" => $user,
            "message" => "Created user successfully",
        ];

        return $message;

    }
}
