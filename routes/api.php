<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/test',[UserController::class,'index'])->name('test.index');
Route::get('/searchUser',[UserController::class,'show'])->name('user.search');
Route::put('/updateScore/{id}',[UserController::class,'update'])->name('user.update.score');
Route::post('/createUser',[UserController::class,'store'])->name('user.create');
Route::delete('/deleteUser/{id}',[UserController::class,'destroy'])->name('user.delete');