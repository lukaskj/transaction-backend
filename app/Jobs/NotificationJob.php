<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationJob implements ShouldQueue {
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $tries = 5;
   public $backoff = 3;

   private User $user;
   private string $message;

   public function __construct(User $user, string $message) {
      $this->user = $user;
      $this->message = $message;
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle() {
      NotificationService::notify($this->user, $this->message);
   }
}
