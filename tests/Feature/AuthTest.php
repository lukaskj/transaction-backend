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
         'password' => '123456',
      ]);

      $response->assertStatus(401);
      $response->assertJsonStructure([
         'status',
         'reason',
         'data',
      ]);
      $response->assertJson([
         'status' => 'error',
      ]);
   }

   public function testValidLogin() {
      $response = $this->postJson('/oapi/v1/login', [
         'email' => 'cliente1@clientes.com',
         'password' => '123456',
      ]);

      $response->assertStatus(200);
      $response->assertJsonStructure([
         'status',
         'data' => [
            'token',
            'expire_date',
         ],
      ]);
   }

   public function testInvalidRegistration() {
      $response = $this->postJson('/oapi/v1/register', [
         'email' => 'cliente1@clientes.com',
         'password' => '',
      ]);

      $response->assertStatus(422);

      $response->assertJson([
         'status' => 'error',
      ]);

      $response->assertJsonStructure([
         'status',
         'data' => [
            'email',
            'person_company_id',
            'password',
            'name',
         ],
      ]);
   }

   public function testValidRegistration() {
      $faker = \Faker\Factory::create('pt_BR');
      $response = $this->postJson('/oapi/v1/register', [
         'email' => 'test@test.com',
         'password' => '123456',
         'person_company_id' => $faker->cpf(true),
         'name' => $faker->name,
      ]);

      $response->assertStatus(200);

      $response->assertJson([
         'status' => 'ok',
      ]);

      $response->assertJsonStructure([
         'status',
         'data',
      ]);
   }

   public function testAuthenticatedEndpointError() {
      $response = $this->getJson('/api/v1/me');
      $response->assertStatus(401);

      $response->assertJson([
         'status' => 'error',
         'reason' => 'unauthenticated',
      ]);

      $response->assertJsonStructure([
         'status',
         'reason',
         'data',
      ]);
   }

   public function testAuthenticatedEndpointSuccess() {
      $loginResponse = $this->postJson('/oapi/v1/login', [
         'email' => 'cliente1@clientes.com',
         'password' => '123456',
      ])->json();

      $response = $this->getJson('/api/v1/me', [
         'Authorization' => "Bearer {$loginResponse['data']['token']}",
      ]);

      $response->assertStatus(200);

      $response->assertJsonStructure([
         'status',
         'data' => [
            'name',
            'email',
            'person_company_id'
         ],
      ]);

      $response->assertJson([
         'status' => 'ok',
         'data' => [
            'email' => 'cliente1@clientes.com'
         ]
      ]);

   }
}
