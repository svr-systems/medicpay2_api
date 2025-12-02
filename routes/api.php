<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorSpecialtyController;
use App\Http\Controllers\FacturapiDataController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserBankDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFiscalDataController;
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
  Route::get('consultation/info', [ConsultationController::class, 'getInfo']);
  Route::post('doctors', [DoctorController::class, 'publicStore']);
  Route::get('catalogs/specialties', [SpecialtyController::class, 'index']);
});

Route::group(['middleware' => 'auth:api'], function () {
  Route::post('logout', [AuthController::class, 'logout'])
  ;
  //Catalogs
  Route::apiResource('/catalogs/specialties', SpecialtyController::class);
  Route::post('/catalogs/specialties/restore', [SpecialtyController::class, 'restore']);
  Route::get('/catalogs/{catalog}', [CatalogController::class, 'index']);

  //Consultations
  Route::group(['prefix' => 'consultations'], function () {
    Route::post('restore', [ConsultationController::class, 'restore']);
  });
  Route::apiResource('consultations', ConsultationController::class);

  //Patients
  Route::group(['prefix' => 'patients'], function () {
    Route::post('restore', [PatientController::class, 'restore']);
    Route::post('search', [PatientController::class, 'search']);
  });
  Route::apiResource('patients', PatientController::class);

  //User bank data
  Route::group(['prefix' => 'user_bank_data'], function () {
    Route::post('restore', [UserBankDataController::class, 'restore']);
    Route::post('valid', [UserBankDataController::class, 'valid']);
    Route::post('clabe/valid', [UserBankDataController::class, 'clabeValid']);
  });
  Route::apiResource('user_bank_data', UserBankDataController::class);

  //User fiscal data
  Route::group(['prefix' => 'user_fiscal_data'], function () {
    Route::post('restore', [UserFiscalDataController::class, 'restore']);
  });
  Route::apiResource('user_fiscal_data', UserFiscalDataController::class);


  //Doctors
  Route::group(['prefix' => 'doctors'], function () {
    Route::group(['prefix' => 'banks'], function () {
    });

    Route::group(['prefix' => 'specialties'], function () {
      Route::post('valid', [DoctorSpecialtyController::class, 'valid']);
    });

    Route::post('restore', [DoctorController::class, 'restore']);
  });
  Route::apiResource('doctors', DoctorController::class);

  //Hospitals
  Route::group(['prefix' => 'hospitals'], function () {
    Route::post('restore', [HospitalController::class, 'restore']);
  });
  Route::apiResource('hospitals', HospitalController::class);

  //Users

  //User bank data
  Route::group(['prefix' => 'users'], function () {
    //User bank data
    Route::group(['prefix' => 'facturapi'], function () {
      Route::get('organization', [FacturapiDataController::class, 'index']);
      Route::post('organization', [FacturapiDataController::class, 'storeOrganization']);
    });
    Route::post('restore', [UserController::class, 'restore']);
  });
  Route::apiResource('users', UserController::class);
});