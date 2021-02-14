<?php

namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService {
   /**
    * Add transaction record
    *
    * @param int $typeId Transaction type id
    * @param float $amount Transaction amount
    * @param string $description Transaction Description
    * @param int|string $userId Transaction owner
    * @param int|string $userIdRef Transaction referenced
    *
    * @return Transaction
    */
   public function addTransaction(int $typeId, float $amount, string $description, $userId, $userIdRef = null): Transaction {
      return Transaction::query()->create([
         'transaction_type_id' => $typeId,
         'amount' => $amount,
         'description' => $description,
         'user_id' => $userId,
         'user_id_ref' => $userIdRef,
      ]);
   }

   /**
    * Add payment transaction
    * @param int|string|User $payer
    * @param int|string|User $payee
    * @param int $amount Amount to pay
    * @return Transaction First transaction record with
    * @throws Exception
    */
   public function newPayment($payer, $payee, float $amount): Transaction {
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
         $payeeCreditTransaction = $this->addTransaction(
            Transaction::CREDIT,
            $amount,
            'Payment received',
            $payee->id,
            $payer->id
         );

         $payee->balance += $amount;
         $payee->update();

         $payerDebitTransaction = $this->addTransaction(
            Transaction::DEBIT,
            $amount,
            'Payment made',
            $payer->id,
            $payee->id
         );

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

   /**
    * Add user balance with transaction record
    * @param string|int $userId User ID to add founds to
    * @param float $amount Amount to be added. Can be negative and a debit will be made.
    * @return Transaction Transaction record
    * @throws Exception
    */
   public function addFounds($userId, float $amount): Transaction {
      $user = User::find($userId);
      if ($user === null) {
         throw new ReportableException("User not found");
      }

      return DB::transaction(function () use ($user, $amount) {
         if ($user->balance + $amount < 0) {
            throw new ReportableException("Insuficient founds.");
         }

         $user->balance += $amount;
         $user->update();
         return $this->addTransaction(
            $amount > 0 ? Transaction::CREDIT : Transaction::DEBIT,
            abs($amount),
            'Founds added',
            $user->id
         );
      });
   }
}