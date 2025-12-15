<?php

namespace App\Http\Controllers;
use App\Mail\GenAttachmentMailable;
use App\Mail\GenMailable;
use App\Mail\InvoiceAttachmentMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;

class EmailController extends Controller {
  public static function userAccountConfirmation($email, $data) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : env('MAIL_DEBUG');

    if (!GenController::empty($email)) {
      $data->link =
        (GenController::isAppDebug() ? env('SERVER_DEBUG') : env('SERVER')) .
        '/confirmar_cuenta/' .
        Crypt::encryptString($data->id);
      Mail::to($email)->send(new GenMailable($data, 'Confirmar cuenta', 'UserAccountConfirmation'));
    }
  }

  public static function userAccountConfirm($email, $data) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;

    if (!GenController::empty($email)) {
      $data->link = (GenController::isAppDebug() ? env('SERVER_DEBUG') : env('SERVER')) .
        '/iniciar_sesion' .
        '?email=' . $email;
      Mail::to($email)->send(new GenMailable($data, 'Cuenta confirmada', 'UserAccountConfirm'));
    }
  }

  public static function userPasswordRecover($email, $data) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;

    if (!GenController::empty($email)) {
      $data->link =
        (GenController::isAppDebug() ? env('SERVER_DEBUG') : env('SERVER')) .
        '/restablecer_contrasena/' .
        Crypt::encryptString($data->id);
      Mail::to($email)->send(new GenMailable($data, 'Recuperación de contraseña', 'UserPasswordRecover'));
    }
  }

  public static function userPasswordReset($email, $data) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;

    if (!GenController::empty($email)) {
      Mail::to($email)->send(new GenMailable($data, 'Contraseña restablecida', 'UserPasswordReset'));
    }
  }

  public static function userPasswordUpdated($email, $data) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;

    if (!GenController::empty($email)) {
      Mail::to($email)->send(new GenMailable($data, 'Contraseña actualizada', 'UserPasswordUpdated'));
    }
  }

  public static function sendTicket($email, $data, $file_path) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;
    $data->link = (GenController::isAppDebug() ? env('SERVER_DEBUG') : env('SERVER')) .
      '/facturacion/' .
      Crypt::encryptString($data->consultation_id);

    if (!GenController::empty($email)) {
      Mail::to($email)->send(new GenAttachmentMailable($data, 'Ticket', 'SendTicket', $file_path));
    }
  }

  public static function sendConsultation($email, $data, $file_path) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;
    $data->link = (GenController::isAppDebug() ? env('SERVER_DEBUG') : env('SERVER')) .
      '/pagoConsulta/' .
      Crypt::encryptString($data->consultation_id);

    if (!GenController::empty($email)) {
      Mail::to($email)->send(new GenAttachmentMailable($data, 'Consulta registrada', 'SendConsultation', $file_path));
    }
  }

  public static function sendInvoiceFiles($email, $data, $file_path_xml, $file_path_pdf) {
    $email = GenController::isAppDebug() ? env('MAIL_DEBUG') : $email;

    if (!GenController::empty($email)) {
      Mail::to($email)->send(new InvoiceAttachmentMailable($data, 'Facturación', 'SendInvoice', $file_path_xml, $file_path_pdf));
    }
  }
}
