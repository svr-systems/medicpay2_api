<?php

namespace App\Http\Controllers;
use App\Mail\GenAttachmentMailable;
use App\Mail\GenMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;

class EmailController extends Controller
{
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

    if (!GenController::empty($email)) {
      Mail::to($email)->send(new GenAttachmentMailable($data, 'Ticket', 'SendTicket',$file_path));
    }
  }
}
