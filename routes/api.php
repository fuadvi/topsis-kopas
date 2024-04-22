<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\JurusanPnlController;
use App\Http\Controllers\JurusanSmkController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTitleController;
use App\Http\Controllers\ResultAnswerController;
use App\Http\Controllers\SubCriteriaController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UploadImageController;
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

Route::controller(AuthController::class)
    ->group(function (){
        Route::post('login','login');
        Route::post('register','register');
        Route::post('logout/{user}','logout')->middleware('auth:sanctum');
    });

Route::get('jurusan-smk', JurusanSmkController::class);
Route::apiResource('users', UserController::class);

Route::middleware('auth:sanctum')
    ->group(function (){

        Route::get('category', QuestionTitleController::class);
        Route::apiResource('questions', QuestionController::class);

        Route::post('answer', AnswerController::class);
        Route::post('upload', UploadImageController::class);

        Route::controller(ResultAnswerController::class)
            ->group(function (){
                Route::get('overall-answer-results', 'index');
                Route::get('answer-results', 'detail');
                Route::get('charts', 'pieChart');
            });


        Route::controller(JurusanPnlController::class)
            ->group(function (){
                Route::post('jurusan-pnl/{jurusan_pnl}/criteria','addCriteriaJurusan');
                Route::delete('jurusan-pnl/{jurusan_pnl}/criteria','deleteCriteriaJurusan');
                Route::post('jurusan-pnl/{jurusan_pnl}/subject','addBobotSubjectJurusan');
            });
        Route::apiResource('jurusan-pnl', JurusanPnlController::class);

        Route::controller(CriteriaController::class)
            ->group(function (){
                Route::get('criteria-drop-down','dropDownCriteria');
                Route::post('criteria/{criterion}/bobot','addBobotCriteria');
                Route::delete('criteria/{criterion}/bobot/{bobotId}','removeBobotCriteria');
            });

        Route::apiResource('criteria', CriteriaController::class);
        Route::apiResource('subjects', SubjectController::class);

        Route::get('subcriteria-drop-down',[SubCriteriaController::class,'dropDown']);
        Route::apiResource('subcriteria', SubCriteriaController::class);
    });


