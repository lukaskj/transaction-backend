<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserBalance extends Command {
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'user:balance {userId} {amount}';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Set user balance';

   /**
    * Create a new command instance.
    *
    * @return void
    */
   public function __construct() {
      parent::__construct();
   }

   /**
    * Execute the console command.
    *
    * @return int
    */
   public function handle() {
      $user = User::find($this->argument('userId'));
      $amount = (int) $this->argument('amount');

      if ($user === null) {
         $this->error("User not found");
         return 1;
      }

      if ($amount < 0) {
         $this->error("Amount must be positive.");
         return 1;
      }
      $user->balance = $amount;
      $user->update();
      return 0;
   }
}
