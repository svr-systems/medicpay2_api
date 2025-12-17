<?php

namespace App\Models;

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\GenController;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Consultation extends Model
{
  use HasFactory;

  protected function serializeDate(DateTimeInterface $date): string
  {
    return $date->format('Y-m-d H:i:s');
  }

  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data)
  {
    $rules = [
      'consultation_amount' => 'required|numeric',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getUiid($id)
  {
    return 'C-' . str_pad($id, 4, '0', STR_PAD_LEFT);
  }

  static public function getItems($req)
  {
    $doctor = Doctor::where('user_id', $req->user()->id)->first();

    if (!$doctor) {
      return null;
    }

    $items = Consultation::query()
      ->where('is_active', boolval($req->is_active))
      ->where('doctor_id', $doctor->id)
      ->orderBy('created_at')
      ->get([
        'id',
        'is_active',
        'patient_id',
        'consultation_amount'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->patient = Patient::getItem(null, $item->patient_id);
      $item->uiid = Consultation::getUiid($item->id);
    }

    return $items;
  }

  static public function getItem($req, $id)
  {
    $doctor = Doctor::where('user_id', $req->user()->id)->first();

    $item = Consultation::where('id', $id)->
      where('doctor_id', $doctor->id)->
      first([
        'id',
        'is_active',
        'patient_id',
        'consultation_amount'
      ]);

    $item->uiid = Consultation::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->patient = Patient::getItem(null, $item->patient_id);

    return $item;
  }

  static public function getItemEmail($req, $id)
  {
    $doctor = Doctor::getItemByUserId($req->user()->id);

    $item = Consultation::where('id', $id)->
      where('doctor_id', $doctor->id)->
      first([
        'id',
        'is_active',
        'created_at',
        'patient_id',
        'charge_amount'
      ]);

    $item->uiid = Consultation::getUiid($item->id);
    $item->patient = Patient::getItem(null, $item->patient_id);

    $email_data = Consultation::getEmailData($item, $doctor);

    return $email_data;
  }

  static public function getInfoByFolio($folio)
  {
    $created_at = '20' .
      substr($folio, 0, 2) . '-' .
      substr($folio, 2, 2) . '-' .
      substr($folio, 4, 2) . ' ' .
      substr($folio, 6, 2) . ':' .
      substr($folio, 8, 2) . ':' .
      substr($folio, 10, 2);

    $patient_id = (int) substr($folio, 12, 4);

    $item = Consultation::where('patient_id', $patient_id)->
      where('created_at', $created_at)->
      first([
        'id',
        'is_active',
        'created_at',
        'patient_id',
        'charge_amount',
        'doctor_id',
        'transaction_id',
        'patient_invoice_id'
      ]);

    if ($item) {
      $item->uiid = Consultation::getUiid($item->id);
      $item->patient = Patient::getItem(null, $item->patient_id);

      $doctor = Doctor::getItem(null, $item->doctor_id);
      if ($item->transaction_id === null || $item->patient_invoice_id === null) {
        $email_data = Consultation::getEmailData($item, $doctor);
        return $email_data;
      } else {
        $paid_info = new \stdClass;
        $paid_info->is_paid = ($item->transaction_id) ? true : false;
        $paid_info->is_stamped = ($item->patient_invoice_id) ? true : false;

        return $paid_info;
      }
    }
    return $item;
  }

  static public function getEmailData($item, $doctor)
  {
    $email_data = new \stdClass;

    $email_data->consultation_id = $item->id;
    $email_data->uiid = $item->uiid;
    $email_data->folio = ConsultationController::getConsultationFolio($item);
    $email_data->date = Carbon::parse($item->created_at)->toDateTimeString();
    $email_data->doctor = GenController::getFullName($doctor->user);
    $email_data->patient = GenController::getFullName($item->patient->user);
    $email_data->charge_amount = $item->charge_amount;
    $email_data->is_paid = ($item->transaction_id) ? true : false;
    $email_data->is_stamped = ($item->patient_invoice_id) ? true : false;

    return $email_data;
  }

  static public function getGeneral($id)
  {
    $item = Consultation::where('id', $id)->
      first([
        'id',
        'is_active',
        'patient_id',
        'consultation_amount'
      ]);

    $item->uiid = Consultation::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->patient = Patient::getItem(null, $item->patient_id);

    return $item;
  }

  static public function getItemById($id)
  {

    $item = Consultation::where('id', $id)->
      first([
        'id',
        'is_active',
        'patient_id',
        'doctor_id',
        'consultation_amount',
        'created_at',
        'charge_amount',
        'transaction_id',
        'patient_invoice_id',
        'created_at as created_at_label',
      ]);

    $item->uiid = Consultation::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->patient = Patient::getItem(null, $item->patient_id);
    $item->created_at_label = Carbon::parse($item->created_at)->toDateTimeString();

    return $item;
  }
}
