<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase {

   /**
    * Test login request
    */
   public function testInvalidLogin() {
      $response = $this->postJson('/oapi/v1/login', [
         'email' => 'invalid@invalid.com',
         'password' => '123456'
      ]);

      $response->assertStatus(401);
      $response->assertJsonStructure([
         'status',
         'reason',
         'data'
      ]);
      $response->assertJson([
         'status' => 'error'
      ]);
   }

   public function testValidLogin() {
      $response = $this->postJson('/oapi/v1/login', [
         'email' => 'cliente1@clientes.com',
         'password' => '123456'
      ]);

      $response->assertStatus(200);
      $response->assertJsonStructure([
         'status',
         'data' => [
            'token',
            'expire_date'
         ]
      ]);
   }
}
