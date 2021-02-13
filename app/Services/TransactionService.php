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
    * @return Transaction First transaction record with 
    * @throws Exception
    */
   public function newTransaction($payer, $payee, float $amount): Transaction {
      if (!($payer instanceof User)) {
         $payer = User::query()->find($payer);
         if ($payer === null) {
            throw new ReportableException("Payer not found.");
         }

      }

      if (!($payee instanceof User)) {
         $payee = User::query()->find($payee);
         if ($payer === null) {
            throw new ReportableException("Payee not found.");
         }
      }

      if ($payee->id === $payer->id) {
         throw new ReportableException("Payer must not be the same as the payee");
      }

      $this->userCanPay($payer, $amount);

      return DB::transaction(function () use ($payer, $payee, $amount) {
         $payeeCreditTransaction = Transaction::query()->create([
            'transaction_type_id' => 1,
            'amount' => $amount,
            'description' => 'Payment received',
            'user_id' => $payee->id,
            'user_id_ref' => $payer->id,
         ]);

         $payee->balance += $amount;
         $payee->update();

         $payerDebitTransaction = Transaction::query()->create([
            'transaction_type_id' => 2,
            'amount' => $amount,
            'description' => 'Payment made',
            'user_id' => $payer->id,
            'user_id_ref' => $payee->id,
         ]);

         $payeeCreditTransaction->transaction_id_ref = $payerDebitTransaction->id;
         $payeeCreditTransaction->update();

         $payer->balance -= $amount;
         $payer->update();

         return $payeeCreditTransaction;
      });
      return null;
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