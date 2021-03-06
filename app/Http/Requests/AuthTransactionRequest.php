<?php

namespace App\Http\Requests;

use App\Rules\LoggedUserAsPayer;

class AuthTransactionRequest extends AbstractRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
         'value' => ['required', 'numeric', 'min:0'],
         'payer' => ['required', 'exists:users,id', new LoggedUserAsPayer()],
         'payee' => ['required', 'exists:users,id'],
      ];
    }

    /**
     * Custom messages
     *
     * @return array
     */
    public function messages()
    {
        return [
         'payer.exists' => 'Payer not found.',
         'payee.exists' => 'Payee not found.'
      ];
    }
}
