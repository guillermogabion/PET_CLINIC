<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ScheduleController;


Route::group(['prefix' => '/v1'], function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    // Route::get('export', 'UserController@export');
    Route::get('cert/{id}', [PetController::class, 'printCert']);



});
Route::group(['prefix' => '/v1', 'middleware' => ['auth:admin-api']], function () {

    Route::get('self', 'UserController@self');
    Route::get('user', 'UserController@index');
 
    Route::post('storeClient', [PatientController::class, 'storeClient']);
    Route::put('editClient/{id}', [PatientController::class, 'editClient']);
    Route::get('getClient', [PatientController::class, 'getAll']);
    Route::get('getPet', [PetController::class, 'getAllPet']);
    Route::post('insert-pet', [PetController::class, 'store']);
    Route::delete('delete-pet/{id}', [PetController::class, 'deletePet']);
    Route::put('edit-pet/{id}', [PetController::class, 'editPet']);

    Route::post('add-record', [RecordController::class, 'store']);

    Route::get('get-record', [RecordController::class, 'getRecord']);


    Route::post('add-schedule', 'RecordController@storeSched');
    Route::get('get-schedule', [ScheduleController::class, 'get']);
    Route::put('edit-schedule/{id}', [ScheduleController::class, 'edit']);

    Route::resource('schedule', 'ScheduleController');

    Route::post('sendSms', 'RecordController@sendSms');


});
