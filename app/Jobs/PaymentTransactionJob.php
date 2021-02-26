<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentTransactionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Transaction $transaction;
    private TransactionService $transactionService;
    private UserService $userService;

    // public $timeout = 5;
    // public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionService = new TransactionService();
        $this->userService = new UserService();
    }

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->transaction->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Job started | ' . PaymentTransactionJob::class . ' | Attempt: ' . $this->attempts());
        $this->transactionService->transactionJob($this->transaction);
        Log::info('Job ended | ' . PaymentTransactionJob::class . ' | Attempt: ' . $this->attempts());
    }
}
