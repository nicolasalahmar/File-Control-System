<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransformerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkIn/{id}', [TransformerController::class, 'transform'])->name('checkIn');
    Route::get('/getFiles', [TransformerController::class, 'transform'])->name('getFiles');
});

Route::post('/auth/logIn',[TransformerController::class,'transform'])->name('logIn');
Route::post('/auth/logOut',[TransformerController::class,'transform'])->middleware(['auth:sanctum'])->name('logOut');
Route::post('/auth/register',[TransformerController::class,'transform'])->name('register');
