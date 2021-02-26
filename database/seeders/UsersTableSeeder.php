<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\AuthService;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authService = app(AuthService::class);
        $faker = Factory::create('pt_BR');

        $password = '123456';

        $emails = [
         'loja1@lojas.com',
         'loja2@lojas.com',
         'cliente1@clientes.com',
         'cliente2@clientes.com',
      ];

        foreach ($emails as $email) {
            if (!User::query()->where('email', $email)->exists()) {
                $name = strpos($email, 'loja') !== false ? $faker->company : $faker->name;
                $personCompanyId = strpos($email, 'loja') !== false ? $faker->cnpj(false) : $faker->cpf(false);
                $user = $authService->register($name, $email, $personCompanyId, $password);
                Artisan::call('user:balance', ['userId' => $user->id, 'amount' => 500]);
            }
        }
    }
}
