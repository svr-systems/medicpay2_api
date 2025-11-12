<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::post('ticket/email', [TicketController::class, 'sendTicket']);

Route::group(['prefix' => 'public'], function () {
  Route::group(['prefix' => 'users'], function () {
    Route::group(['prefix' => 'password'], function () {
      Route::group(['prefix' => 'reset'], function () {
        Route::post('{id}', [UserController::class, 'passwordReset']);
        Route::get('{id}', [UserController::class, 'getItemPasswordReset']);
      });
      Route::post('recover', [UserController::class, 'passwordRecover']);
    });

    Route::group(['prefix' => 'account_confirm'], function () {
      Route::post('{id}', [UserController::class, 'accountConfirm']);
      Route::get('{id}', [UserController::class, 'getItemAccountConfirm']);
    });
  });
});

Route::group(['middleware' => 'auth:api'], function () {
  Route::post('logout', [AuthController::class, 'logout']);

  Route::get('/catalogs/{catalog}', [CatalogController::class, 'index']);

  //Hospitals
  Route::group(['prefix' => 'hospitals'], function () {
    Route::post('restore', [HospitalController::class, 'restore']);
  });
  Route::apiResource('hospitals', HospitalController::class);

  //Users
  Route::apiResource('users', UserController::class);
});