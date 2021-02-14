<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionController extends Controller {
   public function makeTransaction(TransactionRequest $request) {
      $transaction = (new TransactionService)
         ->newPayment(
            $request->payer,
            $request->payee,
            $request->value
         );
      return response()->success($transaction);
   }

   public function getTransactionList() {
      return response()->success(Transaction::fromUser(auth()->user()->id)->get());
   }
}
