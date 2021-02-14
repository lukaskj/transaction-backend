<?php

namespace Tests\Unit;

use App\Exceptions\ReportableException;
use App\Models\TransactionType;
use App\Models\User;
use App\Services\TransactionService;
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

   public function testIfStoreCannotPay() {
      $service = new TransactionService();

      $storeUser = User::query()
         ->where('account_type', 2)
         ->where('balance', '>', 0)->first();
      $this->assertNotNull($storeUser);

      $canPay = $service->userCanPay($storeUser, 1, false);
      $this->assertEquals(false, $canPay);
   }

   public function testIfClientCanPay() {
      $service = new TransactionService();

      $user = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();
      $this->assertNotNull($user);

      $canPayOverBalance = $service->userCanPay($user, $user->balance + 1000, false);
      $this->assertEquals(false, $canPayOverBalance);

      $canPay = $service->userCanPay($user, $user->balance - 10, false);
      $this->assertEquals(true, $canPay);
   }

   public function testInvalidTransactionPayment() {
      $service = new TransactionService();
      $user1 = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();

      $amount = 5073;

      $this->expectException(ReportableException::class);
      $service->newPayment($user1->id, $user1->id, $amount);
   }

   public function testValidTransactionPayment() {
      $service = new TransactionService();
      $user1 = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();
      $user1Balance = $user1->balance;

      $user2 = User::query()
         ->where('id', '<>', $user1->id)
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();
      $user2Balance = $user2->balance;

      $amount = 56;

      $service->newPayment($user1->id, $user2->id, $amount);

      $user1 = User::query()->find($user1->id);
      $user2 = User::query()->find($user2->id);

      $this->assertEquals($user1Balance - $amount, $user1->balance);
      $this->assertEquals($user2Balance + $amount, $user2->balance);
   }

   public function testAddFounds() {
      $service = new TransactionService();
      $user = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();
      $userInitialBalance = $user->balance;
      $amount = 150.71;
      $transaction = $service->addFounds($user->id, $amount);
      $this->assertNotNull($transaction);
      $user->refresh();
      $this->assertEquals($userInitialBalance + $amount, $user->balance);
   }
}
