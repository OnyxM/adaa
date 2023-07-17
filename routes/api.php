<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BeatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post("login", [AuthController::class, "login"]);
    Route::post("register", [AuthController::class, "register"]);
    Route::post("logout", [AuthController::class, "logout"]);
    Route::post("refresh", [AuthController::class, "refresh"]);
});

Route::group(['middleware'=>'auth'], function () {

    Route::group(['prefix' => 'post'], function(){
        Route::post('create', [PostController::class, "create"]);
        Route::post('like', [PostController::class, "like"]);
    });

    Route::group(['prefix' => 'beat'], function(){
        Route::post('create', [BeatController::class, "create"]);
        Route::post('like', [BeatController::class, "like"]);
    });
});

Route::get("post/{slug}", [PostController::class, "details"]);
Route::get("beat/{slug}", [BeatController::class, "details"]);
