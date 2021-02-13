<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
   protected $fillable = [
      'transaction_type_id',
      'amount',
      'description',
      'user_id',
      'user_id_to',
      'status',
   ];

   public function transaction_type() {
      return $this->hasOne(TransactionType::class);
   }

   public function getAmountAttribute($value) {
      return $value / 100;
   }

   public function setAmountAttribute($value) {
      $this->attributes['amount'] = $value * 100;
   }
}
