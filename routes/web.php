<?php

use App\Http\Controllers\FacturapiController;
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

  $req->bank_code =  "12";
  $req->response_code =  "00";
  $req->card_number =  "47729100****70";
  $req->legend =  "APROBADA 521143";
  $req->card_product =  "c";
  $req->merchant =  "SVR";
  $req->reading_mode =  "07";
  $req->transaction_type =  "Venta";
  $req->cardholder_name =  "EJ. Carlos Torres";
  $req->arqc =  "5B67FE1DE35269CC";
  $req->transaction_sequence =  "312611";
  $req->affiliation =  "4871827";
  $req->authorization_code =  "521143";
  $req->terminal_number =  "2";
  $req->financial_reference =  "113554121416";
  $req->aid =  "A0000000032010";
  $req->status =  "completada";
  $req->charge_amount =  "800.00";
  $req->consultation_id =  29;
  $req->is_credit = false;
  $req->operation_date = '2025-12-16 05:38:44';
  $req->previous_balance_amount = 10;
  $req->previous_balance_points = 10;
  $req->current_balance_amount = 10;
  $req->current_balance_points = 10;
  $req->points_redeemed = 10;
  $req->amount_redeemed = 10;
  $req->payment_form_id = 4;

  $pdf = new PdfController;
  
  return $pdf->ticketOnlinePayment($req);

  $pdf = new TicketController();
  
  return $pdf->sendTicketOnlinePayment($req);
});

Route::get('cargo', [OpenpayController::class, 'getCharge']);
Route::get('invoice', [FacturapiController::class, 'testingInvoice']);
Route::get('invoice/patient', [FacturapiController::class, 'testingPatientInvoice']);
Route::get('consultations/doctor/stamp/{consultation_id}', [FacturapiController::class, 'doctorConsultationStamp']);
