<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class ConsultationTransaction extends Model {
  use HasFactory;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data) {
    $rules = [
      // 'subdomain' => 'required|string|min:2|max:30',
      // 'name' => 'required|string|min:2|max:100',
      // 'fee' => 'required|integer|between:1,30',
      // 'logo_path' => 'exclude_if:logo_doc,null|image|mimes:jpg,jpeg,png|max:2048',
      // 'zip' => 'nullable|digits:5',
      // 'municipality_id' => 'required|numeric',
      // 'street' => 'nullable|min:2|max:75',
      // 'exterior' => 'nullable|min:1|max:15',
      // 'interior' => 'nullable|min:1|max:15',
      // 'neighborhood' => 'nullable|min:2|max:75',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getUiid($id) {
    return 'CT-' . str_pad($id, 4, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = ConsultationTransaction::
      where('is_active', boolval($req->is_active))->
      where('consultation_id',$req->consultation_id);

    $items = $items->
      get();

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = ConsultationTransaction::getUiid($item->id);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = ConsultationTransaction::find($id);

    $item->uiid = ConsultationTransaction::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);

    return $item;
  }
}
