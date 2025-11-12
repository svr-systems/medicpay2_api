<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('ticket', function () {
  $req = new \stdClass();

  $req->estado = "completada";
  $req->afiliacion = "4871827";
  $req->autorizacion = "614465";
  $req->numero_tarjeta = "47729100****70";
  $req->banco = "12";
  $req->arqc = "5B67FE1DE35269CC";
  $req->debito_credito = "c";
  $req->tipo_transaccion = "Venta";
  $req->cliente = "EJ. Carlos Torres";
  $req->modo_lectura = "07";
  $req->leyenda = "APROBADA 614465";
  $req->codigo_respuesta = "00";
  $req->comercio = "SVR";
  $req->sec_txn = "241544";
  $req->caja = "2";
  $req->aid = "A0000000032010";
  $req->referencia_financiera = "342625345142";
  $req->monto = 1000;
  $req->tarjeta_ambiente = "PAYWAVE/VISA";

  $pdf = new PdfController;
  
  return $pdf->ticket($req);
});
