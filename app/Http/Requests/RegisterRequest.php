<?php

namespace App\Http\Requests;

class RegisterRequest extends AbstractRequest {
   public function authorize() {
      return true;
   }

   public function rules() {
      return [
         'name' => ['required'],
         'email' => ['required', 'email', 'unique:App\Models\User,email'],
         'password' => ['required', 'min:5'],
         'person_company_id' => ['required', 'unique:App\Models\User,person_company_id'],
      ];
   }
}
