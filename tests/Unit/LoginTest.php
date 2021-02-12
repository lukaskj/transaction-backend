<?php

namespace Tests\Unit;

use App\Services\AuthService;
use Tests\TestCase;

class LoginTest extends TestCase {
   /**
    * A basic test example.
    *
    * @return void
    */
   public function testBasicLogin() {
      $token = (new AuthService)->login('loja1@lojas.com', '123456');
      $this->assertNotNull($token);
      $this->assertJson($token);
   }
}
