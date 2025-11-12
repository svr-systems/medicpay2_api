<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Throwable;

class TicketController extends Controller {
  public function sendTicket(Request $req) {
    try {
      $data = null;
      $pdf = new PdfController;
      $file_path = $pdf->ticket($req);
      EmailController::sendTicket(null, $data, $file_path);
      Storage::delete($file_path);
      return $this->apiRsp(
        200,
        'Ticket enviado correctamente',
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
