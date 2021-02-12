<?php

namespace Database\Seeders;

use App\Models\User;
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
      $faker = Factory::create('pt_BR');

      $passwordHash = Hash::make('123456');

      $storeEmail = 'loja1@lojas.com';
      if (!User::query()->where('email', $storeEmail)->exists()) {
         User::create([
            'email' => $storeEmail,
            'person_company_id' => $faker->cnpj(false),
            'name' => $faker->company,
            'email_verified_at' => now(),
            'password' => $passwordHash,
         ]);
      }

      $store2Email = 'loja2@lojas.com';
      if (!User::query()->where('email', $store2Email)->exists()) {
         User::create([
            'email' => $store2Email,
            'person_company_id' => $faker->cnpj(false),
            'name' => $faker->company,
            'email_verified_at' => now(),
            'password' => $passwordHash,
         ]);
      }

      $client1Email = 'cliente1@clientes.com';
      if (!User::query()->where('email', $client1Email)->exists()) {
         User::create([
            'email' => $client1Email,
            'person_company_id' => $faker->cpf(false),
            'name' => $faker->company,
            'email_verified_at' => now(),
            'password' => $passwordHash,
         ]);
      }

      $client2Email = 'cliente2@clientes.com';
      if (!User::query()->where('email', $client2Email)->exists()) {
         User::create([
            'email' => $client2Email,
            'person_company_id' => $faker->cpf(false),
            'name' => $faker->company,
            'email_verified_at' => now(),
            'password' => $passwordHash,
         ]);
      }
   }
}
