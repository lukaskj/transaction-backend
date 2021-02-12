<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase {
   /**
    * A basic test example.
    *
    * @return void
    */
   public function testIfAppIsUp() {
      $response = $this->get('/oapi/v1/status');

      $response
         ->assertStatus(200)
         ->assertJsonStructure([
            'status',
         ])
         ->assertJson([
            'status' => 'ok',
         ]);
   }
}
