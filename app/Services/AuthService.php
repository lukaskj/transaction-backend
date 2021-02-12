<?php

namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\User;
use App\Models\UserToken;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService {

   /**
    * Login method
    * @throws Exception
    */
   public function login(string $email, $password): UserToken {
      $user = User::where('email', $email)->first();

      if (is_null($user) || !Hash::check($password, $user->password)) {
         throw new ReportableException("Wrong email or password.", null, 401);
      }

      $userToken = null;
      DB::beginTransaction();
      try {
         UserToken::where('user_id', $user->id)
            ->delete();
         $userToken = UserToken::create(['user_id' => $user->id]);
         DB::commit();
         return $userToken;
      } catch (\Exception $e) {
         DB::rollBack();
         throw $e;
      }
   }
}