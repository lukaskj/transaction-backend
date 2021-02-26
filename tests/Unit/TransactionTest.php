<?php

namespace Tests\Unit;

use App\Exceptions\ReportableException;
use App\Models\TransactionType;
use App\Models\User;
use App\Services\TransactionService;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    private TransactionService $service;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(TransactionService::class);
    }

    public function testIfTransactionTypesExists()
    {
        $credit = TransactionType::query()->find(1);
        $debit = TransactionType::query()->find(2);

        $this->assertEquals(1, $credit->multiplier);
        $this->assertEquals(-1, $debit->multiplier);
    }

    public function testIfStoreCannotPay()
    {
        $storeUser = User::query()
         ->where('account_type', 2)
         ->where('balance', '>', 0)->first();

        $canPay = $this->service->userCanPay($storeUser, 1, false);
        $this->assertEquals(false, $canPay);
    }

    public function testIfClientCanPay()
    {
        $user = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();

        $canPayOverBalance = $this->service->userCanPay($user, $user->balance + 1000, false);
        $this->assertEquals(false, $canPayOverBalance);

        $canPay = $this->service->userCanPay($user, $user->balance - 10, false);
        $this->assertEquals(true, $canPay);
    }

    public function testInvalidTransactionPayment()
    {
        $user1 = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();

        $amount = 5073;

        $this->expectException(ReportableException::class);
        $this->service->newPayment($user1->id, $user1->id, $amount);
    }

    public function testValidTransactionPayment()
    {
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

        $this->service->newPayment($user1->id, $user2->id, $amount);

        $user1 = User::query()->find($user1->id);
        $user2 = User::query()->find($user2->id);

        $this->assertEquals($user1Balance - $amount, $user1->balance);
        $this->assertEquals($user2Balance + $amount, $user2->balance);
    }

    public function testAddFounds()
    {
        $user = User::query()
         ->where('account_type', 1)
         ->where('balance', '>', 0)->first();
        $userInitialBalance = $user->balance;
        $amount = 150.71;
        $transaction = $this->service->addFounds($user->id, $amount);
        $this->assertNotNull($transaction);
        $user->refresh();
        $this->assertEquals($userInitialBalance + $amount, $user->balance);
    }
}
