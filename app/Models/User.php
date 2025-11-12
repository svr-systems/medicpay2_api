<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\DocMgrController;
use App\Http\Controllers\GenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use DateTimeInterface;
use Validator;

class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
    'email_verified_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function validEmail($data, $id) {
    $rules = ['email' => 'required|string|min:2|max:65|regex:/.+@.+\..+/|unique:users,email,' . $id];

    $msgs = ['email.unique' => 'El E-mail ya ha sido registrado'];

    return Validator::make($data, $rules, $msgs);
  }

  public static function validPassword($data) {
    $rules = ['password' => 'required|string|min:8|max:30|regex:/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@$%*])/'];

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  public static function valid($data, $is_req = true) {
    $rules = [
      'role_id' => 'required|numeric',
      'name' => 'required|min:2|max:50',
      'paternal_surname' => 'required|min:2|max:25',
      'maternal_surname' => 'nullable|min:2|max:25',
      'curp' => 'required|min:18',
      'phone' => 'nullable|min:10',
    ];

    if (!$is_req) {
      array_push($rules, ['active' => 'required|in:true,false,1,0']);
    }

    $msgs = [];

    return Validator::make($data, $rules, $msgs);
  }

  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }



  static public function getItems($req) {
    $items = User::
      where('active', boolval($req->active));

    $items = $items->
      orderBy('name')->
      orderBy('surname_p')->
      orderBy('surname_m')->
      get([
        'id',
        'active',
        'name',
        'surname_p',
        'surname_m',
        'email',
        'role_id',
        'email_verified_at',
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->full_name = GenController::getFullName($item);
      $item->role = Role::find($item->role_id, ['name']);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = User::
      find($id, [
        'id',
        'active',
        'created_at',
        'updated_at',
        'created_by_id',
        'updated_by_id',
        'email_verified_at',
        'name',
        'paternal_surname',
        'maternal_surname',
        'curp',
        'email',
        'avatar',
        'role_id',
      ]);

    if ($item) {
      $item->created_by = User::find($item->created_by_id, ['email']);
      $item->updated_by = User::find($item->updated_by_id, ['email']);
      $item->full_name = GenController::getFullName($item);
      $item->avatar_b64 = DocMgrController::getB64($item->avatar, 'User');
      $item->avatar_doc = null;
      $item->avatar_dlt = false;
      $item->role = Role::find($item->role_id, ['name']);
    }

    return $item;
  }

  static public function getItemByEmail($email) {
    $item = User::
      where('email', $email)->
      first();

    return $item;
  }
}