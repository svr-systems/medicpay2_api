<?php

namespace App\Models;

use App\Http\Controllers\DocMgrController;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Hospital extends Model {
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
      'subdomain' => 'required|string|min:2|max:30',
      'name' => 'required|string|min:2|max:100',
      'fee' => 'required|integer|between:1,30',
      'logo_path' => 'exclude_if:logo_doc,null|image|mimes:jpg,jpeg,png|max:2048',
      'zip' => 'nullable|digits:5',
      'municipality_id' => 'required|numeric',
      'street' => 'nullable|min:2|max:75',
      'exterior' => 'nullable|min:1|max:15',
      'interior' => 'nullable|min:1|max:15',
      'neighborhood' => 'nullable|min:2|max:75',
    ];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  static public function getUiid($id) {
    return 'H-' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = Hospital::
      where('is_active', boolval($req->is_active));

    $items = $items->
      orderBy('name')->
      get([
        'id',
        'is_active',
        'name',
        'fee',
        'municipality_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->municipality = Municipality::find($item->municipality_id, ['name', 'state_id']);
      $item->municipality->state = State::find($item->municipality->state_id, ['name']);
      $item->uiid = Hospital::getUiid($item->id);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = Hospital::find($id);
    
    $item->uiid = Hospital::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->logo_b64 = DocMgrController::getB64($item->logo_path, 'Hospital');
    $item->logo_doc = null;
    $item->logo_dlt = false;
    $item->municipality = Municipality::find($item->municipality_id, ['name', 'state_id']);
    $item->municipality->state = State::find($item->municipality->state_id, ['name']);

    return $item;
  }
}
