<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesTableSeeder extends Seeder {
   public function run() {
      if (!TransactionType::query()->where('id', 1)->exists()) {
         TransactionType::query()->create([
            'id' => 1,
            'description' => 'Credit',
            'multiplier' => 1,
         ]);
      }

      if (!TransactionType::query()->where('id', 2)->exists()) {
         TransactionType::query()->create([
            'id' => 2,
            'description' => 'Debit',
            'multiplier' => -1,
         ]);
      }
   }
}
