<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AbstractRequest extends FormRequest {
   protected function failedValidation(Validator $validator) {
      throw new \App\Exceptions\ReportableException("Invalid request data", $validator->errors(), 422);
   }
}