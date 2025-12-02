<?php

namespace App\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;

class PdfController extends Controller {
  private $fpdf;
  public function ticket($req) {
    try {
      if (isset($req->puntos_redimidos)) {
        $document_type = 2;
      } elseif (isset($req->meses)) {
        $document_type = 3;
      } else {
        $document_type = 1;
      }

      $this->fpdf = new Fpdf;
      $this->fpdf->AddPage();

      switch ($req->modo_lectura) {
        case '05':
          $modo_ingreso = 'I@1';
          break;
        case '01':
          $modo_ingreso = 'T1';
          break;
        case '80':
          $modo_ingreso = 'D@1';
          break;
        case '90':
          $modo_ingreso = 'D@1';
          break;
        case '07':
          $modo_ingreso = 'C@1';
          break;
        case '91':
          $modo_ingreso = 'C@1';
          break;
        default:
          $modo_ingreso = '';
      }
      $x = 65;
      $y_ini = $this->fpdf->GetY() - 5;
      $this->fpdf->SetFont('times', '', 10);
      $this->fpdf->SetXY($x, 10);
      $this->fpdf->Cell(80, 5, utf8_decode('BBVA'), 0, 0, 'C');
      $y = $this->fpdf->GetY() + 5;
      $this->fpdf->Image(Storage::disk('public')->path('logo-negro.png'), 90, $y, 30, 7, 'png');
      $y += 7;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('AVENIDA IRRIGACION 103-LOCAL 13 C'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('AFILIACIÓN: ' . $req->afiliacion), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(strtoupper(str_replace('_', ' ', $req->tipo_transaccion))), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('TARJETA: ' . $req->numero_tarjeta), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode($req->tarjeta_ambiente), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      if ($req->debito_credito === 'c') {
        $this->fpdf->Cell(80, 5, utf8_decode('TARJETA DE CRÉDITO'), 0, 0, 'C');
      } else {
        $this->fpdf->Cell(80, 5, utf8_decode('TARJETA DE DÉBITO'), 0, 0, 'C');
      }
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('__________________________'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode($req->leyenda), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode($modo_ingreso . ' ARQC: ' . $req->arqc), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('AID: ' . $req->aid), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('__________________________'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(40, 5, utf8_decode('IMPORTE '), 0, 0, 'L');
      $this->fpdf->SetXY($x + 40, $y);
      $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->monto) . " MXN"), 0, 0, 'R');
      if ($document_type === 2) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('PAGADO CON PUNTOS '), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->pesos_redimidos) . " MXN"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('TOTAL A PAGAR '), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat(($req->monto - $req->pesos_redimidos)) . " MXN"), 0, 0, 'R');
      }
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('REF: ' . $req->referencia_financiera), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('SEC TXN: ' . $req->sec_txn), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('CAJA: ' . $req->caja), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
      if ($document_type === 1) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->MultiCell(80, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      } elseif ($document_type === 2) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->MultiCell(80, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
        $y = $this->fpdf->GetY();
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode('PUNTOS BBVA'), 0, 0, 'C');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode('-----------------------------'), 0, 0, 'C');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('Saldo Ant Pesos:'), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->anterior_saldopesos) . " MXN"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('Saldo Ant Puntos:'), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode(GenController::numericFormat($req->anterior_saldopuntos) . " PTS"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('Saldo Disp Pesos:'), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->saldo_pesos) . " MXN"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('Saldo Disp Puntos:'), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode(GenController::numericFormat($req->saldo_puntos) . " PTS"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('Pesos Redimidos:'), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->pesos_redimidos) . " MXN"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('Puntos Redimidos:'), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode(GenController::numericFormat($req->puntos_redimidos) . " PTS"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode('-----------------------------'), 0, 0, 'C');
      } elseif ($document_type === 3) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode('TRES MESES SIN INTERESES'), 0, 0, 'C');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->MultiCell(80, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      }

      $y = $this->fpdf->GetY() + 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      if ($req->modo_lectura === '07') {
        $this->fpdf->Cell(80, 5, utf8_decode('AUTORIZADO SIN FIRMA'), 0, 0, 'C');
      } else {
        $this->fpdf->Cell(80, 5, utf8_decode('AUTORIZADO MEDIANTE FIRMA ELECTRÓNICA'), 0, 0, 'C');
      }
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(date('Y-m-d H:i:s')), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('Pagaré negociable únicamente'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('en instituciones de crédito.'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('C   L   I   E   N   T   E'), 0, 0, 'C');

      $this->fpdf->Line($x - 5, $y_ini, $x + 85, $y_ini);
      //line bot
      $this->fpdf->Line($x - 5, $y + 10, $x + 85, $y + 10);
      //line left
      $this->fpdf->Line($x - 5, $y_ini, $x - 5, $y + 10);
      //line right
      $this->fpdf->Line($x + 85, $y_ini, $x + 85, $y + 10);

      ////////////////////////CLIENTE////////////////////////////
      $this->fpdf->AddPage();

      switch ($req->modo_lectura) {
        case '05':
          $modo_ingreso = 'I@1';
          break;
        case '01':
          $modo_ingreso = 'T1';
          break;
        case '80':
          $modo_ingreso = 'D@1';
          break;
        case '90':
          $modo_ingreso = 'D@1';
          break;
        case '07':
          $modo_ingreso = 'C@1';
          break;
        case '91':
          $modo_ingreso = 'C@1';
          break;
        default:
          $modo_ingreso = '';
      }
      $x = 65;
      $y_ini = $this->fpdf->GetY() - 5;
      $this->fpdf->SetFont('times', '', 10);
      $this->fpdf->SetXY($x, 10);
      $this->fpdf->Cell(80, 5, utf8_decode('BBVA'), 0, 0, 'C');
      $y = $this->fpdf->GetY() + 5;
      $this->fpdf->Image(Storage::disk('public')->path('logo-negro.png'), 90, $y, 30, 7, 'png');
      $y += 7;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('AVENIDA IRRIGACION 103-LOCAL 13 C'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('AFILIACIÓN: ' . $req->afiliacion), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(strtoupper(str_replace('_', ' ', $req->tipo_transaccion))), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('TARJETA: ' . $req->numero_tarjeta), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode($req->tarjeta_ambiente), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      if ($req->debito_credito === 'c') {
        $this->fpdf->Cell(80, 5, utf8_decode('TARJETA DE CRÉDITO'), 0, 0, 'C');
      } else {
        $this->fpdf->Cell(80, 5, utf8_decode('TARJETA DE DÉBITO'), 0, 0, 'C');
      }
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('__________________________'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode($req->leyenda), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode($modo_ingreso . ' ARQC: ' . $req->arqc), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('AID: ' . $req->aid), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('__________________________'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(40, 5, utf8_decode('IMPORTE '), 0, 0, 'L');
      $this->fpdf->SetXY($x + 40, $y);
      $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->monto) . " MXN"), 0, 0, 'R');
      if ($document_type === 2) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('PAGADO CON PUNTOS '), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->pesos_redimidos) . " MXN"), 0, 0, 'R');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(40, 5, utf8_decode('TOTAL A PAGAR '), 0, 0, 'L');
        $this->fpdf->SetXY($x + 40, $y);
        $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat(($req->monto - $req->pesos_redimidos)) . " MXN"), 0, 0, 'R');
      }
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('REF: ' . $req->referencia_financiera), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('SEC TXN: ' . $req->sec_txn), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('CAJA: ' . $req->caja), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
      if ($document_type === 1) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->MultiCell(80, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      } elseif ($document_type === 2) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->MultiCell(80, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
        // $y = $this->fpdf->GetY();
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(80, 5, utf8_decode('PUNTOS BBVA'), 0, 0, 'C');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(80, 5, utf8_decode('-----------------------------'), 0, 0, 'C');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode('Saldo Ant Pesos:'), 0, 0, 'L');
        // $this->fpdf->SetXY($x + 40, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->anterior_saldopesos) . " MXN"), 0, 0, 'R');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode('Saldo Ant Puntos:'), 0, 0, 'L');
        // $this->fpdf->SetXY($x + 40, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode(GenController::numericFormat($req->anterior_saldopuntos) . " PTS"), 0, 0, 'R');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode('Saldo Disp Pesos:'), 0, 0, 'L');
        // $this->fpdf->SetXY($x + 40, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->saldo_pesos) . " MXN"), 0, 0, 'R');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode('Saldo Disp Puntos:'), 0, 0, 'L');
        // $this->fpdf->SetXY($x + 40, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode(GenController::numericFormat($req->saldo_puntos) . " PTS"), 0, 0, 'R');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode('Pesos Redimidos:'), 0, 0, 'L');
        // $this->fpdf->SetXY($x + 40, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode("$" . GenController::moneyFormat($req->pesos_redimidos) . " MXN"), 0, 0, 'R');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode('Puntos Redimidos:'), 0, 0, 'L');
        // $this->fpdf->SetXY($x + 40, $y);
        // $this->fpdf->Cell(40, 5, utf8_decode(GenController::numericFormat($req->puntos_redimidos) . " PTS"), 0, 0, 'R');
        // $y += 5;
        // $this->fpdf->SetXY($x, $y);
        // $this->fpdf->Cell(80, 5, utf8_decode('-----------------------------'), 0, 0, 'C');
      } elseif ($document_type === 3) {
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode('TRES MESES SIN INTERESES'), 0, 0, 'C');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
        $y += 5;
        $this->fpdf->SetXY($x, $y);
        $this->fpdf->MultiCell(80, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      }

      $y = $this->fpdf->GetY() + 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      if ($req->modo_lectura === '07') {
        $this->fpdf->Cell(80, 5, utf8_decode('AUTORIZADO SIN FIRMA'), 0, 0, 'C');
      } else {
        $this->fpdf->Cell(80, 5, utf8_decode('AUTORIZADO MEDIANTE FIRMA ELECTRÓNICA'), 0, 0, 'C');
      }
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(date('Y-m-d H:i:s')), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('Pagaré negociable únicamente'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('en instituciones de crédito.'), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode(''), 0, 0, 'C');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell(80, 5, utf8_decode('C   O   M   E   R   C   I   O'), 0, 0, 'C');

      $title = "Ticket - " . time();

      //line top
      $this->fpdf->Line($x - 5, $y_ini, $x + 85, $y_ini);
      //line bot
      $this->fpdf->Line($x - 5, $y + 10, $x + 85, $y + 10);
      //line left
      $this->fpdf->Line($x - 5, $y_ini, $x - 5, $y + 10);
      //line right
      $this->fpdf->Line($x + 85, $y_ini, $x + 85, $y + 10);

      $filename = public_path('..') . "/storage/app/private/temp/" . $title . ".pdf";
      $this->fpdf->Output($filename, 'F');
      $pdf = file_get_contents($filename);
      $pdf64 = base64_encode($pdf);

      // Storage::disk('temp')->delete($title . ".pdf");

      $data = new \stdClass;
      // $data->pdf64 = $pdf64;
      $data->path = $filename;

      return $filename;

      // return response($this->fpdf->Output('S'))
      //   ->header('Content-Type', 'application/pdf')
      //   ->header('Content-Disposition', 'inline; filename="' . $title . '.pdf"');

    } catch (\Throwable $th) {
      return response()->json([
        "success" => false,
        "message" => "ERR. " . $th
      ], 200);
    }
  }
  public function consultation($data) {
    try {
      $this->fpdf = new Fpdf;
      $this->fpdf->AddPage();

      $x = 10;
      $y = 10;
      $this->fpdf->SetFont('times', '', 15);
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('Consulta'), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('ID: ' . $data->uiid), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('Folio: ' . $data->folio), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('Fecha y hora: ' . $data->date), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('Medico: ' . $data->doctor), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('Paciente: ' . $data->patient), 0, 0, 'L');
      $y += 5;
      $this->fpdf->SetXY($x, $y);
      $this->fpdf->Cell($x, 5, utf8_decode('Monto: $' . GenController::moneyFormat($data->charge_amount) . ' MXN'), 0, 0, 'L');
      $y += 5;

      $title = "Consultation - " . time();

      $folio_encripted = Crypt::encryptString($data->folio);

      $qr_name = 'user_qr_' . $title . '.png';
      \QrCode::format('png')
        ->size(512)
        ->generate(
          $folio_encripted,
          Storage::disk('temp')->path($qr_name)
        );

      //QR en consulta
      $this->fpdf->Image(Storage::disk('temp')->path($qr_name), $x, $y, 50);


      $filename = public_path('..') . "/storage/app/private/temp/" . $title . ".pdf";
      $this->fpdf->Output($filename, 'F');
      $pdf = file_get_contents($filename);
      $pdf64 = base64_encode($pdf);

      // Storage::disk('temp')->delete($title . ".pdf");

      $data = new \stdClass;
      $data->pdf64 = $pdf64;
      $data->path = $filename;

      return $filename;

      // return response($this->fpdf->Output('S'))
      //   ->header('Content-Type', 'application/pdf')
      //   ->header('Content-Disposition', 'inline; filename="' . $title . '.pdf"');

    } catch (\Throwable $th) {
      return response()->json([
        "success" => false,
        "message" => "ERR. " . $th
      ], 200);
    }
  }
}
