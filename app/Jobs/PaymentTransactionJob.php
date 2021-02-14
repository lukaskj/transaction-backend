<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentTransactionJob implements ShouldQueue {
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   private Transaction $transaction;
   private TransactionService $transactionService;
   private UserService $userService;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct(Transaction $transaction) {
      $this->transaction = $transaction;
      $this->transactionService = new TransactionService;
      $this->userService = new UserService;
   }

   /**
    * The number of seconds after which the job's unique lock will be released.
    *
    * @var int
    */
   public $uniqueFor = 3600;

   /**
    * The unique ID of the job.
    *
    * @return string
    */
   public function uniqueId() {
      return $this->transaction->id;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle() {
      try {
         DB::beginTransaction();
         $payer = $this->transaction->user;
         $payee = $this->transaction->user_ref;
         $amount = $this->transaction->amount;
         
         $payeeCreditTransaction = $this->transactionService->addTransaction(
            Transaction::CREDIT,
            $amount,
            'Payment received',
            $payee->id,
            $payer->id,
            $this->transaction->id,
            Transaction::STATUS_CONFIRMED
         );


         $this->userService->updateBalance($payee->id, $payee->balance += $amount);

         $this->transaction->status = Transaction::STATUS_CONFIRMED;
         $this->transaction->update();
         
         DB::commit();
      } catch (\Throwable $th) {
         DB::rollBack();
         throw $th;
      }

      Log::info("AAAAAAAAAAAAAAAAAAAAAA");
      Log::info("AAAAAAAAAAAAAAAAAAAAAA");
      Log::info($this->transaction->id);
      Log::info("AAAAAAAAAAAAAAAAAAAAAA");
      Log::info("AAAAAAAAAAAAAAAAAAAAAA");
   }
}
