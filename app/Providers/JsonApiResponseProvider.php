<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class JsonApiResponseProvider extends ServiceProvider {
   public function boot() {
      Response::macro('success', function ($message = null, $data = null, $status = 200) {
         if (!is_string($message)) {
            $data = $message;
            $message = 'Success';
         }
         $response = [
            'status' => 'ok',
            'message' => $message ?? '',
            'data' => $data ?: null,
         ];

         return Response::json($response, $status);
      });

      Response::macro('error', function ($message = null, \Exception $exception = null, $status = 500) {
         $message = $message ?? 'Error';
         $responseError = [
            'status' => 'error',
            'reason' => \Illuminate\Support\Str::slug($message),
            'message' => $message,
         ];

         if (!is_null($exception) && method_exists($exception, 'getMessage')) {
            report($exception);

            if (config('app.debug')) {
               $responseError['exception'] = $exception->getMessage();
               $responseError['exception_trace'] = $exception->getTraceAsString();
            }
         }

         return Response::json($responseError, $status);
      });
   }
}
