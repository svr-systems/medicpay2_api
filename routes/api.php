<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorSpecialtyController;
use App\Http\Controllers\FacturapiController;
use App\Http\Controllers\FacturapiDataController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\OpenpayController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserBankDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFiscalDataController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

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

  Route::group(['prefix' => 'user_fiscal_data'], function () {
    Route::get('{consultation_id}', [UserFiscalDataController::class, 'getFiscalDataByConsultation']);
    Route::post('{consultation_id}', [UserFiscalDataController::class, 'setFiscalDataByConsultation']);
  });

  Route::post('consultation/doctor/stamp/{consultation_id}', [FacturapiController::class, 'doctorConsultationStamp']);
  Route::post('consultation/payment/card', [OpenpayController::class, 'paymentCard']);
  Route::post('consultations/invoce/stamp', [FacturapiController::class, 'patientConsultationStamp']);
  Route::post('consultations/transactions', [TransactionController::class, 'store']);
  Route::get('consultation/info', [ConsultationController::class, 'getInfo']);
  Route::post('doctors', [DoctorController::class, 'publicStore']);
  Route::get('catalogs/specialties', [SpecialtyController::class, 'index']);
  Route::get('catalogs/{catalog}', [CatalogController::class, 'public']);
  Route::post('ticket/email', [TicketController::class, 'sendTicket']);
});

Route::group(['middleware' => 'auth:api'], function () {
  Route::post('logout', [AuthController::class, 'logout']);

  //Catalogs
  Route::get('/catalogs/{catalog}', [CatalogController::class, 'index']);


  //Consultations
  Route::group(['prefix' => 'consultations'], function () {
    Route::post('restore', [ConsultationController::class, 'restore']);

    //Consultation transactions
    Route::group(['prefix' => 'transactions'], function () {
      Route::post('restore', [TransactionController::class, 'restore']);
    });
    Route::apiResource('transactions', TransactionController::class);
  });
  Route::apiResource('consultations', ConsultationController::class);

  //Patients
  Route::group(['prefix' => 'patients'], function () {
    Route::post('restore', [PatientController::class, 'restore']);
    Route::post('search', [PatientController::class, 'search']);
  });
  Route::apiResource('patients', PatientController::class);

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

  /**
   * ===========================================
   * DOCTOR
   * ===========================================
   */
  Route::group(['prefix' => 'doctor'], function () {
    Route::group(['prefix' => 'user_bank_data'], function () {
      Route::post('valid', [UserBankDataController::class, 'valid']);
      Route::post('clabe/valid', [UserBankDataController::class, 'clabeValid']);
    });
    Route::apiResource('user_bank_data', UserBankDataController::class);

    Route::group(['prefix' => 'facturapi_data'], function () {
      Route::get('', [FacturapiDataController::class, 'index']);
      Route::post('', [FacturapiDataController::class, 'storeOrganization']);
    });

    Route::apiResource('user_fiscal_data', UserFiscalDataController::class);
  });

  /**
   * ===========================================
   * SYSTEM
   * ===========================================
   */
  Route::group(['prefix' => 'specialties'], function () {
    Route::post('restore', [SpecialtyController::class, 'restore']);
  });
  Route::apiResource('specialties', SpecialtyController::class);

  Route::group(['prefix' => 'users'], function () {
    Route::post('restore', [UserController::class, 'restore']);
  });
  Route::apiResource('users', UserController::class);
});