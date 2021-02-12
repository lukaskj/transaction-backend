<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AuthService;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run() {
      $authService = new AuthService();
      $faker = Factory::create('pt_BR');

      $password = '123456';

      $store1Email = 'loja1@lojas.com';
      if (!User::query()->where('email', $store1Email)->exists()) {
         $authService->register($faker->company, $store1Email, $faker->cnpj(false), $password);
      }

      $store2Email = 'loja2@lojas.com';
      if (!User::query()->where('email', $store2Email)->exists()) {
         $authService->register($faker->company, $store2Email, $faker->cnpj(false), $password);
      }

      $client1Email = 'cliente1@clientes.com';
      if (!User::query()->where('email', $client1Email)->exists()) {
         $authService->register($faker->name, $client1Email, $faker->cpf(false), $password);
      }

      $client2Email = 'cliente2@clientes.com';
      if (!User::query()->where('email', $client2Email)->exists()) {
         $authService->register($faker->name, $client2Email, $faker->cpf(false), $password);
      }
   }
}
