<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserToken extends AbstractUuidModel {
   use SoftDeletes;

   protected $table = "user_token";

   protected $fillable = [
      "token",
      "expire_date",
      "user_agent",
      "ip",
      "user_id",
   ];

   protected $hidden = [
      "ip",
      "user_agent",
      'deleted_at',
   ];

   protected static function boot() {
      parent::boot();
      static::creating(function (Model $model) {
         $model->setAttribute('expire_date', Carbon::now()->add(config('token.valid_amount'), config('token.valid_metric')));
         $model->setAttribute('user_agent', request()->server('HTTP_USER_AGENT'));
         $model->setAttribute('ip', request()->ip());
         $model->setAttribute('token', base64_encode(Hash::make(Str::random(16) . Carbon::now())));
      });
   }

   public function user() {
      return $this->belongsTo(User::class);
   }
}
