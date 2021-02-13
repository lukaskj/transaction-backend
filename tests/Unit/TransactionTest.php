<?php

namespace Tests\Unit;

use App\Models\TransactionType;
use Tests\TestCase;

class TransactionTest extends TestCase {

   public function testIfTransactionTypesExists() {
      $credit = TransactionType::query()->find(1);
      $debit = TransactionType::query()->find(2);
      $this->assertNotNull($credit);
      $this->assertNotNull($debit);
      $this->assertEquals(1, $credit->multiplier);
      $this->assertEquals(-1, $debit->multiplier);
   }
}
