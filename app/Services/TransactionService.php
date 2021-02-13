<?php

namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService {
   /**
    * Add payment transaction
    * @param int|string|User $payer
    * @param int|string|User $payee
    * @param int $amount Amount to pay
    * @return Transaction
    * @throws Exception
    */
   public function pay($payer, $payee, int $amount) {
      if (!($payer instanceof User)) {
         $payer = User::query()->find($payer);
      }

      if (!($payee instanceof User)) {
         $payee = User::query()->find($payee);
      }

      $this->userCanPay($payer, $amount);

      DB::transaction(function () use ($payer, $payee, $amount) {
         Transaction::query()->create([
            'transaction_type_id' => 1,
            'amount' => $amount,
            'description' => 'Payment received',
            'user_id' => $payee->id,
            'user_id_ref' => $payer->id,
         ]);

         $payee->balance += $amount;
         $payee->update();

         Transaction::query()->create([
            'transaction_type_id' => 2,
            'amount' => $amount,
            'description' => 'Payment made',
            'user_id' => $payer->id,
            'user_id_ref' => $payee->id,
         ]);

         $payer->balance -= $amount;
         $payer->update();
      });

   }

   /**
    * Checks if user can pay
    *
    * @param User $payer
    * @param int $amount Amount to pay
    * @param bool $throwExceptions Throw exceptions. 'false' only returns boolean.
    */
   public function userCanPay(User $payer, int $amount, bool $throwExceptions = true): bool {
      if ($payer->isStore()) {
         if ($throwExceptions) {
            throw new ReportableException("Store cannot make payments.", null, 422);
         } else {
            return false;
         }
      }

      if ($payer->balance < $amount) {
         if ($throwExceptions) {
            throw new ReportableException("Insufficient funds.", ['amountToPay' => $amount / 100, 422]);
         } else {
            return false;
         }
      }

      return true;
   }
}