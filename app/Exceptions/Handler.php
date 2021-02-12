<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {
   /**
    * A list of the exception types that are not reported.
    *
    * @var array
    */
   protected $dontReport = [
      //
   ];

   /**
    * A list of the inputs that are never flashed for validation exceptions.
    *
    * @var array
    */
   protected $dontFlash = [
      'password',
      'password_confirmation',
   ];

   /**
    * Register the exception handling callbacks for the application.
    *
    * @return void
    */
   public function register() {
      $this->reportable(function (Throwable $e) {
         //
      });
   }

   protected function convertExceptionToArray(Throwable $e) {
      $response = parent::convertExceptionToArray($e);
      $reason = \Illuminate\Support\Str::slug($response['message'] ?: $e->getMessage());
      $response['status'] = 'error';
      $response['reason'] = $reason;
      $response['data'] = null;
      if ($e instanceof ReportableException) {
         $response['message'] = $e->getMessage();
         $response['data'] = $e->getData();
      }
      return $response;
   }
}
