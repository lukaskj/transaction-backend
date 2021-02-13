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

   /**
    * Get amount from float to int
    * @return float
    */
   public function getAmountAttribute($value) {
      return $value / 100;
   }

   /**
    * Set amount from float to int
    */
   public function setAmountAttribute($value) {
      $this->attributes['amount'] = (int) ($value * 100);
   }

   /**
    * Get raw amount as integer
    * @return integer
    */
   public function getRawAmountAttribute() {
      return $this->attributes['amount'];
   }

   public function user() {
      return $this->hasOne(User::class);
   }

   public function user_ref() {
      return $this->hasOne(User::class, 'user_id_ref');
   }
}
