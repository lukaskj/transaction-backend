<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function makeAuthTransaction(AuthTransactionRequest $request, TransactionService $service)
    {
        $transaction = $service
         ->newPayment(
             $request->payer,
             $request->payee,
             $request->value
         );
        return response()->success($transaction);
    }

    public function makeTransaction(TransactionRequest $request, TransactionService $service)
    {
        $transaction = $service
         ->newPayment(
             $request->payer,
             $request->payee,
             $request->value
         );
        return response()->success($transaction);
    }

    public function getTransactionList()
    {
        return response()->success(Transaction::fromUser(auth()->user()->id)->get());
    }
}
