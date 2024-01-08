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
    Route::get('/checkIn/{id}', [TransformerController::class, 'transform'])->name('file.checkIn');
    Route::post('/checkOut', [TransformerController::class, 'transform'])->name('file.checkOut');
    Route::get('/getMyFiles', [TransformerController::class, 'transform'])->name('file.getMyFiles');
    Route::post('/bulkCheckIn', [TransformerController::class, 'transform'])->name('file.bulkCheckIn');
    Route::post('/uploadFiles', [TransformerController::class, 'transform'])->name('file.uploadFiles');
    Route::post('/files/removeFiles', [TransformerController::class, 'transform'])->name('file.removeFiles');
    Route::post('/createGroup', [TransformerController::class, 'transform'])->name('group.createGroup');
    Route::post('/addFilesToGroup',[TransformerController::class, 'transform'])->name('group.addFilesToGroup');
    Route::post('/addUsersToGroup',[TransformerController::class, 'transform'])->name('group.addUsersToGroup');
    Route::post('/removeFilesFromGroup',[TransformerController::class, 'transform'])->name('group.removeFilesFromGroup');
    Route::post('/removeUsersFromGroup',[TransformerController::class, 'transform'])->name('group.removeUsersFromGroup');
    Route::get('/readFile/{id}',[TransformerController::class, 'transform'])->name('file.readFile');
    Route::get('/downloadFile/{id}',[TransformerController::class, 'downloadFile'])->name('file.downloadFile');
    Route::get('/MyGroups', [TransformerController::class, 'transform'])->name('group.MyGroups');
    Route::get('/enrolledGroups', [TransformerController::class, 'transform'])->name('group.enrolledGroups');
    Route::get('/removeGroup/{id}', [TransformerController::class, 'transform'])->name('group.removeGroup');
    Route::get('/filesInGroup/{id}', [TransformerController::class, 'transform'])->name('group.filesInGroup');

});

Route::middleware(['role:Admin','auth:sanctum'])->group(function () {
    Route::get('/ExportOperationsReport', [TransformerController::class, 'transform'])->name('log.ExportOperationsReport');
    Route::get('/FileReports', [TransformerController::class, 'transform'])->name('log.FileReports');
});

Route::middleware(['role:User','auth:sanctum'])->group(function () {
    Route::get('/UserFileReports', [TransformerController::class, 'transform'])->name('log.UserFileReports');
});


Route::post('/auth/logIn',[TransformerController::class,'transform'])->name('user.logIn');
Route::post('/auth/logOut',[TransformerController::class,'transform'])->middleware(['auth:sanctum'])->name('user.logOut');
Route::get('/allUsers',[TransformerController::class,'transform'])->middleware(['auth:sanctum'])->name('user.allUsers');
Route::post('/auth/register',[TransformerController::class,'transform'])->name('user.register');
