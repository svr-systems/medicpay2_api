<?php

namespace App\Http\Controllers;

use App\Models\PaymentForm;
use Crypt;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Storage;

class PdfController extends Controller {
  private $fpdf;
  public function ticket($req) {
    try {
      if (isset($req->points_redeemed)) {
        $document_type = 2;
      } elseif (isset($req->is_credit)) {
        $document_type = 3;
      } else {
        $document_type = 1;
      }

      switch ($req->reading_mode) {
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

      $this->fpdf = new Fpdf('P', 'mm', [80, 260]);
      $this->fpdf->SetAutoPageBreak(true, 6);
      $this->fpdf->SetMargins(5, 5, 5);
      $this->fpdf->AddPage();

      $this->pdfCenter('BBVA', 10, 'times', 'B');

      $logo_w = 30;
      $logo_h = 7;
      $logo_x = ($this->fpdf->GetPageWidth() - $logo_w) / 2;

      $this->fpdf->Image(
        Storage::disk('public')->path('logo-negro.png'),
        $logo_x,
        $this->fpdf->GetY(),
        $logo_w,
        $logo_h,
        'png'
      );
      
      $this->fpdf->Ln(8);
      $this->pdfCenter('AVENIDA IRRIGACION 103-LOCAL 13 C', 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('AFILIACIÓN: ' . $req->affiliation, 10);
      $this->fpdf->Ln(5);
      $this->pdfCenter(strtoupper(str_replace('_', ' ', $req->transaction_type)), 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('TARJETA: ' . $req->card_number, 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter($req->cardholder_name, 10);
      if ($req->card_product === 'c') {
        $this->pdfCenter('TARJETA DE CRÉDITO', 10);
      } else {
        $this->pdfCenter('TARJETA DE DÉBITO', 10);
      }
      $this->fpdf->Ln(1);
      $this->pdfCenter('__________________________', 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter($req->legend, 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter($modo_ingreso . ' ARQC: ' . $req->arqc, 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('AID: ' . $req->aid, 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('__________________________', 10);
      $this->fpdf->Ln(1);
      $this->pdfDoubleColumn('IMPORTE ', '$' . GenController::moneyFormat($req->charge_amount) . ' MXN', 10);
      if ($document_type === 2) {
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('PAGADO CON PUNTOS ', '$' . GenController::moneyFormat($req->points_redeemed) . ' MXN', 10);
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('TOTAL A PAGAR ', '$' . GenController::moneyFormat(($req->charge_amount - $req->points_redeemed)) . ' MXN', 10);
      }
      $this->fpdf->Ln(10);
      $this->pdfLeft('REF: ' . $req->financial_reference, 10);
      $this->fpdf->Ln(0);
      $this->pdfLeft('SEC TXN: ' . $req->transaction_sequence, 10);
      $this->fpdf->Ln(0);
      $this->pdfLeft('CAJA: ' . $req->terminal_number, 10);
      $this->fpdf->Ln(3);
      if ($document_type === 1) {
        $this->fpdf->MultiCell(0, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      } elseif ($document_type === 2) {
        $this->fpdf->MultiCell(0, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
        $this->fpdf->Ln(10);
        $this->pdfCenter('PUNTOS BBVA', 10);
        $this->fpdf->Ln(1);
        $this->pdfCenter('-----------------------------', 10);
        $this->fpdf->Ln(1);
        $this->pdfDoubleColumn('Saldo Ant Pesos: ', '$' . GenController::moneyFormat($req->previous_balance_amount) . ' MXN', 10);
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('Saldo Ant Puntos: ', GenController::numericFormat($req->previous_balance_points) . ' PTS', 10);
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('Saldo Disp Pesos: ', '$' . GenController::moneyFormat($req->current_balance_amount) . ' MXN', 10);
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('Saldo Disp Puntos: ', GenController::numericFormat($req->current_balance_points) . ' PTS', 10);
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('Pesos Redimidos: ', '$' . GenController::moneyFormat($req->points_redeemed) . ' MXN', 10);
        $this->fpdf->Ln(5);
        $this->pdfDoubleColumn('Puntos Redimidos: ', GenController::numericFormat($req->amount_redeemed) . ' PTS', 10);
        $this->fpdf->Ln(5);
        $this->pdfCenter('-----------------------------', 10);
      } elseif ($document_type === 3) {
        $this->fpdf->Ln(5);
        $this->pdfCenter('TRES MESES SIN INTERESES', 10);
        $this->fpdf->Ln(5);
        $this->fpdf->MultiCell(0, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      }
      $this->fpdf->Ln(5);
      if ($req->reading_mode === '07') {
        $this->pdfCenter('AUTORIZADO SIN FIRMA', 10);
      } else {
        $this->pdfCenter('AUTORIZADO MEDIANTE FIRMA ELECTRÓNICA', 10);
      }
      $this->fpdf->Ln(5);
      $this->pdfCenter($req->operation_date, 10);
      $this->fpdf->Ln(0);
      $this->pdfCenter('Pagaré negociable únicamente', 10);
      $this->fpdf->Ln(0);
      $this->pdfCenter('en instituciones de crédito.', 10);
      $this->fpdf->Ln(5);
      $this->pdfCenter('C   L   I   E   N   T   E', 10);

      $title = "Ticket - " . time();

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

  public function ticketOnlinePayment($req) {
    try {
      $this->fpdf = new Fpdf('P', 'mm', [80, 150]);
      $this->fpdf->SetAutoPageBreak(true, 6);
      $this->fpdf->SetMargins(5, 5, 5);
      $this->fpdf->AddPage();

      $logo_w = 30;
      $logo_h = 7;
      $logo_x = ($this->fpdf->GetPageWidth() - $logo_w) / 2;

      $this->fpdf->Image(
        Storage::disk('public')->path('logo-negro.png'),
        $logo_x,
        $this->fpdf->GetY(),
        $logo_w,
        $logo_h,
        'png'
      );
      
      $this->fpdf->Ln(8);
      $this->pdfCenter('AVENIDA IRRIGACION 103-LOCAL 13 C', 10);


      $x = 65;
      $y = $this->fpdf->GetY() + 5;

      
      $this->fpdf->Ln(5);
      $this->pdfCenter('VENTA', 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('TARJETA: ' . $req->card_number, 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter($req->cardholder_name, 10);
      $payment_form = PaymentForm::find($req->payment_form_id);
      $this->fpdf->Ln(1);
      $this->pdfCenter(strtoupper($payment_form->name), 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('__________________________', 10);
      $this->fpdf->Ln(1);
      $this->pdfCenter('APROBADA ' . $req->authorization_code, 10);
      $this->pdfCenter('__________________________', 10);
      $this->fpdf->Ln(1);
      $this->pdfDoubleColumn('IMPORTE ', '$' . GenController::moneyFormat($req->charge_amount) . ' MXN', 10);
      $this->fpdf->Ln(10);
      $this->fpdf->MultiCell(0, 5, utf8_decode('Por este pagaré me obligo incondicionalmente a pagar a la orden del banco acreditante el importe de este título. Este pagaré procede del contrato de apertura de crédito que el banco acreditante y el tarjetahabiente tienen celebrado.'), 0, 'J', false);
      $this->fpdf->Ln(5);
      $this->pdfCenter('AUTORIZADO CON VENTA ELECTRÓNICA', 10);
      $this->fpdf->Ln(5);
      $this->pdfCenter($req->operation_date, 10);
      $this->fpdf->Ln(0);
      $this->pdfCenter('Pagaré negociable únicamente', 10);
      $this->fpdf->Ln(0);
      $this->pdfCenter('en instituciones de crédito.', 10);
      $this->fpdf->Ln(5);
      $this->pdfCenter('C   L   I   E   N   T   E', 10);

      $title = "Ticket - " . time();

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
    $pdf = null;
    $qr_path = null;

    try {
      $page_width = 80;
      $page_height = 160;

      $this->fpdf = new Fpdf('P', 'mm', [80, 160]);
      $this->fpdf->SetAutoPageBreak(true, 6);
      $this->fpdf->SetMargins(5, 5, 5);
      $this->fpdf->AddPage();

      $logo_w = 30;
      $logo_h = 7;
      $logo_x = ($this->fpdf->GetPageWidth() - $logo_w) / 2;

      $this->fpdf->Image(
        Storage::disk('public')->path('logo-negro.png'),
        $logo_x,
        $this->fpdf->GetY(),
        $logo_w,
        $logo_h,
        'png'
      );

      $this->fpdf->Ln(14);

      $this->pdfCenter('C O N S U L T A', 13, 'times', 'B');
      $this->fpdf->Ln(4);

      $this->pdfKv('Folio:', (string) $data->folio, 6, 11, 11);
      $this->fpdf->Ln(2);

      $this->pdfKv('Monto:', '$' . GenController::moneyFormat($data->charge_amount) . ' MXN', 6, 11, 11);
      $this->fpdf->Ln(4);

      $this->pdfKv('ID:', (string) $data->uiid, 5, 10, 9);
      $this->pdfKv('Fecha:', (string) $data->date, 5, 10, 9);
      $this->pdfKv('Médico:', (string) $data->doctor, 5, 10, 9);

      $this->fpdf->Ln(6);

      $title = 'consultation_' . time();
      $folio_encrypted = Crypt::encryptString((string) $data->folio);

      $qr_name = 'user_qr_' . $title . '.png';
      $qr_path = Storage::disk('temp')->path($qr_name);

      \QrCode::format('png')
        ->size(512)
        ->generate($folio_encrypted, $qr_path);

      $qr_w = 46;
      $qr_x = ($this->fpdf->GetPageWidth() - $qr_w) / 2;

      $this->fpdf->Image($qr_path, $qr_x, $this->fpdf->GetY(), $qr_w, 0, 'png');

      $filename = public_path('..') . "/storage/app/private/temp/{$title}.pdf";
      $this->fpdf->Output($filename, 'F');

      if (is_file($qr_path)) {
        @unlink($qr_path);
      }

      return $filename;
      // return response($this->fpdf->Output('S'))
      //   ->header('Content-Type', 'application/pdf')
        // ->header('Content-Disposition', 'inline; filename="' . $title . '.pdf"');
    } catch (\Throwable $th) {
      if ($qr_path && is_file($qr_path)) {
        @unlink($qr_path);
      }

      return $th;

      return apiRsp(false, 'ERR.', collect([
        'error' => $th->getMessage(),
      ]));
    }
  }

  private function pdfCenter(string $text, int $size = 12, string $font = 'times', string $style = ''): void {
    $this->fpdf->SetFont($font, $style, $size);
    $this->fpdf->Cell(0, 5, utf8_decode($text), 0, 1, 'C');
  }

  private function pdfLeft(string $text, int $size = 12, string $font = 'times', string $style = ''): void {
    $this->fpdf->SetFont($font, $style, $size);
    $this->fpdf->Cell(0, 5, utf8_decode($text), 0, 1, 'L');
  }

  private function pdfDoubleColumn(string $label, string $value, int $size = 12, string $font = 'times', string $style = ''): void {
    $this->fpdf->SetFont($font, $style, $size);
    $this->fpdf->Cell(0, 5, utf8_decode($label), 0, 0, 'L');
    $this->fpdf->Cell(0, 5, utf8_decode($value), 0, 0, 'R');
  }

  private function pdfKv(
    string $label,
    string $value,
    float $line_h = 6,
    int $label_size = 11,
    int $value_size = 11
  ): void {
    $this->fpdf->SetFont('times', 'B', $label_size);
    $this->fpdf->Cell(0, $line_h, utf8_decode($label), 0, 1, 'C');
    $this->fpdf->SetFont('times', '', $value_size);
    $this->fpdf->MultiCell(0, $line_h, utf8_decode($value), 0, 'C');
  }
}
