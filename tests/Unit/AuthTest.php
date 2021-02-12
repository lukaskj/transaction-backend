<?php

namespace Tests\Unit;

use App\Exceptions\ReportableException;
use App\Services\AuthService;
use Tests\TestCase;

class AuthTest extends TestCase {
   /**
    * @var AuthService
    */
   private $authService;

   public function testBasicLogin() {
      $userToken = (new AuthService)->login('loja1@lojas.com', '123456');
      $this->assertNotNull($userToken);
      $this->assertIsObject($userToken);
      $this->assertNotNull($userToken->token);
      $this->assertNotNull($userToken->expire_date);
   }

   public function testFailedRegistration() {
      $this->expectException(ReportableException::class);
      $user = (new AuthService)->register(
         'User Name Test',
         'usertest@test.com',
         '12345678911',
         '123456'
      );
   }

   public function testBasicRegistration() {
      $user = (new AuthService)->register(
         'User Name Test',
         'usertest@test.com',
         '08888758003',
         '123456'
      );
      $this->assertNotNull($user);
      $this->assertNotNull($user->id);
   }
}
