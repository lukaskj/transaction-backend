<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model {
   use DateFormat;
   
   public const CREDIT = 1;
   public const DEBIT = 2;

   public const STATUS_ERROR = -1;
   public const STATUS_PENDING = 0;
   public const STATUS_CONFIRMED = 1;

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
    * @return BelongsTo
    */
   public function user(): BelongsTo {
      return $this->belongsTo(User::class);
   }

   /**
    * Transaction user reference.
    *
    * Example: User A pays user B
    *   - First transaction record:
    *     - Credit for user A with user B as user_ref
    *   - Second transaction record:
    *     - Debit for user B with user A as user_ref
    * @return BelongsTo
    */
   public function user_ref(): BelongsTo {
      return $this->belongsTo(User::class, 'user_id_ref');
   }

   /**
    * Transaction reference.
    *
    * Example: User A pays user B
    *   - First transaction record:
    *     - Credit for user A with user B as user_ref and transaction_ref is the second transaction ID
    *   - Second transaction record:
    *     - Debit for user B with user A as user_ref and transaction_ref null
    * @return BelongsTo
    */
   public function transaction_ref(): BelongsTo {
      return $this->belongsTo(Transaction::class, 'transaction_ref_id');
   }

   /**
    * Get transactions from user
    */
   public function scopeFromUser($query, $userId) {
      return $query->where('user_id', $userId);
   }
}
