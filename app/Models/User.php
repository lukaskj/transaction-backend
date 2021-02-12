<?php

namespace App\Models;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
   use HasFactory, Notifiable, UuidModel;

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
   protected $casts = [
      'email_verified_at' => 'datetime',
   ];

   public function getIsClientAttribute($value) {
      return $this->isClient();
   }

   public function getIsStoreAttribute($value) {
      return $this->isStore();
   }

   public function isClient() {
      return strlen($this->person_company_id) == 11;
   }

   public function isStore() {
      return strlen($this->person_company_id) == 14;
   }
}
