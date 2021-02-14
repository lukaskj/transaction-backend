<?php

namespace App\Http\Requests;

use App\Rules\PersonCompanyIdRule;
use App\Utils\StringUtil;

class RegisterRequest extends AbstractRequest {
   public function authorize() {
      return true;
   }

   protected function prepareForValidation() {
      $this->merge([
         'person_company_id' => StringUtil::onlyNumbers($this->person_company_id),
      ]);
   }

   public function rules() {
      return [
         'name' => ['required'],
         'email' => ['required', 'email', 'unique:App\Models\User,email'],
         'password' => ['required', 'min:5'],
         'person_company_id' => ['required', 'unique:App\Models\User,person_company_id', new PersonCompanyIdRule],
      ];
   }
}
