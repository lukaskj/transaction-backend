<?php

namespace Tests\Unit;

use App\Services\AuthService;
use Tests\TestCase;

class AuthTest extends TestCase {
   public function testBasicLogin() {
      $userToken = (new AuthService)->login('loja1@lojas.com', '123456');
      $this->assertNotNull($userToken);
      $this->assertIsObject($userToken);
      $this->assertNotNull($userToken->token);
      $this->assertNotNull($userToken->expire_date);
   }
}
