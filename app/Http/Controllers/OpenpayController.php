<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Consultation;
use App\Models\Transaction;
use Crypt;
use DB;
use Illuminate\Http\Request;
use Openpay;
use Throwable;

// require_once '../vendor/autoload.php';

class OpenpayController extends Controller {
  public function paymentCard(Request $req) {
    DB::beginTransaction();
    try {
    $consultation_id = Crypt::decryptString($req->consultation_id);
    $consultation = Consultation::getItemById($consultation_id);

    if($consultation->transaction_id === null){
      $openpay = Openpay::getInstance('mvqklc5fv1rsuqrttndt',
        'sk_60ac024b9ddc4ad8a557603a96c22967'
      );
      // Openpay::setProductionMode(false);
      $customer = array(
        'name' => $req->name,
        'last_name' => $req->last_name,
        'phone_number' => $consultation->patient->user->phone_number,
        'email' => $consultation->patient->user->email
      );

      // return $consultation;

      $chargeData = array(
        'method' => 'card',
        'source_id' => $req->token_id,
        'amount' => $consultation->charge_amount, // formato númerico con hasta dos dígitos decimales. 
        'description' => 'Sin descripción',
        'use_card_points' => $req->use_card_points, // Opcional, si estamos usando puntos
        'device_session_id' => $req->device_session_id,
        'customer' => $customer
      );

      $charge = $openpay->charges->create($chargeData);
      $payment_id = $charge->id;

      //Errores

      $bank_type = BankType::getByCode($charge->card->bank_code);
      $payment_form_id = ($charge->card->type === 'debit')?18:4;
      $operation_date = date('Y-m-d H:i:s',strtotime($charge->operation_date));
      $status = ($charge->status === "completed")?true:false;

      $transaction =  new \stdClass;
      $transaction->consultation_id = $consultation_id;
      $transaction->status = $status;
      $transaction->card_number = str_replace('X','*',$charge->card->card_number);
      $transaction->bank_type_id = $bank_type->id;
      $transaction->payment_form_id = $payment_form_id;
      $transaction->authorization_code = $charge->authorization;
      $transaction->reading_mode = null;
      $transaction->arqc = null;
      $transaction->aid = null;
      $transaction->financial_reference = null;
      $transaction->terminal_number = null;
      $transaction->transaction_sequence = null;
      $transaction->cardholder_name = $charge->card->holder_name;
      $transaction->error_message = $charge->error_message;
      $transaction->response_code = null;
      $transaction->is_points_used = false;
      $transaction->points_redeemed = null;
      $transaction->amount_redeemed = null;
      $transaction->previous_balance_amount = null;
      $transaction->previous_balance_points = null;
      $transaction->current_balance_amount = null;
      $transaction->current_balance_points = null;
      $transaction->operation_date = $operation_date;
      $transaction->payment_id = $payment_id;
      $transaction->charge_amount = $consultation->charge_amount;

      $item = new Transaction;
      $item = TransactionController::saveItem($item,$transaction);

      if($status){
        $consultation = Consultation::find($consultation_id);
        $consultation->transaction_id = $item->id;
        $consultation->save();

        $ticket_controller = new TicketController;
        $ticket_controller->sendTicketOnlinePayment($transaction);
      }

      DB::commit();
        return $this->apiRsp(
          200,
          'Pago realizado correctamente',
          ['item' => $transaction]
        );
      }else{
        return $this->apiRsp(
          200,
          'Esta acción ya hacido realizada, favor de revisar su correo electrónico',
          null
        );
      }
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }
}
