<?php

use App\Http\Controllers\OpenpayController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('consultation', function () {
  $req = new \stdClass();

  $req->uiid =  "C-0027";
  $req->folio =  "2512011601460012";
  $req->date =  "Monday  1 de December de 2025";
  $req->doctor =  "ADMIN SISTEMA";
  $req->patient =  "PACIENTE EN CONSULTA";
  $req->charge_amount =  600;

  $pdf = new PdfController;
  
  return $pdf->consultation($req);
});

Route::get('ticket', function () {
  $req = new \stdClass();

  $operation_date = date('Y-m-d H:i:s',strtotime("2025-12-10T16:51:10-06:00"));
  $req->consultation_id = "29";
  $req->status = true;
  $req->card_number = "411111******1111";
  $req->bank_type_id = 143;
  $req->payment_form_id = 18;
  $req->authorization_code = "801585";
  $req->reading_mode = null;
  $req->arqc = null;
  $req->aid = null;
  $req->financial_reference = null;
  $req->terminal_number = null;
  $req->transaction_sequence = null;
  $req->cardholder_name = "Er Levi";
  $req->error_message = null;
  $req->response_code = null;
  $req->is_points_used = false;
  $req->points_redeemed = null;
  $req->amount_redeemed = null;
  $req->previous_balance_amount = null;
  $req->previous_balance_points = null;
  $req->current_balance_amount = null;
  $req->current_balance_points = null;
  $req->operation_date = $operation_date;
  $req->payment_id = "tru8nufmikh0en2zrkz0";
  $req->customer_id = null;
  $req->charge_amount = "800.00";

  $pdf = new TicketController();
  
  return $pdf->sendTicketOnlinePayment($req);
});

Route::get('cargo', [OpenpayController::class, 'getCharge']);
