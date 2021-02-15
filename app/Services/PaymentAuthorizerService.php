<?php

namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Http;

// Static methods to be easier to use
class PaymentAuthorizerService {
   private const URL = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";

   /**
    * Authorize transaction with external service
    * 
    * @param Transaction $transaction
    * @return bool
    * @throws Exception
    */
   public static function authorize(Transaction $transaction): bool {
      if (config('app.env') === 'testing') {
         return true;
      }

      try {
         $response = Http::post(self::URL);
         $json = $response->json();
         if (!isset($json["message"]) || strtolower($json["message"]) !== "autorizado") {
            throw new ReportableException("Transaction not authorized.", null, 401);
         }
         return true;
      } catch (\Throwable $th) {
         report($th);
         throw new ReportableException("Transaction not authorized.", $th->getMessage(), 401);
      }
   }
}