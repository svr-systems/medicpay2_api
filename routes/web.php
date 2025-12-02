<?php

use App\Http\Controllers\PdfController;
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
