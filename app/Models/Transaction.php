<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model {
   public const CREDIT = 1;
   public const DEBIT = 2;

   protected $fillable = [
      'transaction_type_id',
      'amount',
      'description',
      'user_id',
      'user_id_ref',
      'transaction_id_ref',
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
    * @return void
    */
   public function setAmountAttribute($value) {
      $this->attributes['amount'] = (int) ($value * 100);
   }

   /**
    * Get raw amount as integer
    *
    * @return integer
    */
   public function getRawAmountAttribute() {
      return $this->attributes['amount'];
   }

   /**
    * Transaction owner user
    * @return HasOne
    */
   public function user(): HasOne {
      return $this->hasOne(User::class);
   }

   /**
    * Transaction user reference.
    *
    * Example: User A pays user B
    *   - First transaction record:
    *     - Credit for user A with user B as user_ref
    *   - Second transaction record:
    *     - Debit for user B with user A as user_ref
    * @return HasOne
    */
   public function user_ref(): HasOne {
      return $this->hasOne(User::class, 'user_id_ref');
   }

   /**
    * Transaction reference.
    *
    * Example: User A pays user B
    *   - First transaction record:
    *     - Credit for user A with user B as user_ref and transaction_ref is the second transaction ID
    *   - Second transaction record:
    *     - Debit for user B with user A as user_ref and transaction_ref null
    * @return HasOne
    */
   public function transaction_ref() {
      return $this->hasOne(Transaction::class, 'transaction_ref_id');
   }
}
