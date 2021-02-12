<?php

namespace App\Providers;

use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider {
   /**
    * The policy mappings for the application.
    *
    * @var array
    */
   protected $policies = [
      // 'App\Models\Model' => 'App\Policies\ModelPolicy',
   ];

   /**
    * Register any authentication / authorization services.
    *
    * @return void
    */
   public function boot() {
      $this->registerPolicies();

      $this->app['auth']->viaRequest('token', function ($request) {
         $header = $request->header('authorization', '');
         $token = null;
         if (!Str::startsWith($header, 'Bearer ')) {
            return null;
         }
         $token = Str::substr($header, 7);
         $userToken = UserToken::where('token', $token)->where('expire_date', '>', Carbon::now())->first();

         return is_null($userToken) ? null : $userToken->user;
      });
   }
}
