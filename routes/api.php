<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JurusanSmkController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTitleController;
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


Route::controller(AuthController::class)
    ->group(function (){
        Route::post('login','login');
        Route::post('register','register');
        Route::post('logout/{user}','logout')->middleware('auth:sanctum');
    });

Route::get('jurusan-smk', JurusanSmkController::class);

Route::middleware('auth:sanctum')
    ->group(function (){
        Route::get('category', QuestionTitleController::class);

        Route::apiResource('questions', QuestionController::class);
    });
