<?php

namespace App\Services;

use App\Models\User;

class UserService {
   /**
    * Update user balance
    * @param int|string $userId
    * @param float balance
    */
   public function updateBalance($userId, float $balance): User {
      $user = User::find($userId);
      $user->balance = $balance;
      $user->update();
      return $user;
   }
}