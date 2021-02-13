<?php

namespace App\Http\Requests;

use App\Rules\LoggedUserAsPayer;

class TransactionRequest extends AbstractRequest {

   public function authorize() {
      return true;
   }

   public function rules() {
      return [
         'value' => ['required', 'numeric', 'min:0'],
         'payer' => ['required', new LoggedUserAsPayer], // Strange input. The payer must be the logged in user. Unless another service later do the transactions.
         'payee' => ['required', 'exists:users,id'],
      ];
   }

   /**
    * Custom messages
    *
    * @return array
    */
   public function messages() {
      return [
         'payee.exists' => 'Payee not found.'
      ];
   }
}
