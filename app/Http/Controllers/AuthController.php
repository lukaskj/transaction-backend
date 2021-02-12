<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller {
   public function login(LoginRequest $request) {
      $userToken = (new AuthService)->login($request->email, $request->password);
      return response()->success($userToken->only('token', 'expire_date'));
   }

   public function register(RegisterRequest $request) {
      $user = (new AuthService)->register(
         $request->name,
         $request->email,
         $request->person_company_id,
         $request->password,
      );
      return response()->success();
   }
}
