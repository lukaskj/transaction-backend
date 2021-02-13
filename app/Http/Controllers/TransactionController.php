<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;

class TransactionController extends Controller {
   public function makeTransaction(TransactionRequest $request) {
      $transaction = (new TransactionService)
         ->newTransaction(
            $request->payer,
            $request->payee,
            $request->value
         );
      return response()->success($transaction);
   }
}
