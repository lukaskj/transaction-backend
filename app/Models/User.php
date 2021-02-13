<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Autenticable;
use Illuminate\Notifications\Notifiable;

class User extends Autenticable {
   use HasFactory, Notifiable;

   protected $fillable = [
      'name',
      'email',
      'person_company_id',
      'account_type',
      'password',
      'email_verified_at',
   ];

   protected $hidden = [
      'password',
      'remember_token',
      'created_at',
      'updated_at',
      'email_verified_at',
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

   /**
    * Get balance from float to int
    * @return float
    */
   public function getBalanceAttribute($value) {
      return $value / 100;
   }

   /**
    * Set balance from float to int
    */
   public function setBalanceAttribute($value) {
      $this->attributes['balance'] = (int) ($value * 100);
   }

   /**
    * Get raw balance as integer
    * @return integer
    */
   public function getRawBalanceAttribute() {
      return $this->attributes['balance'];
   }
}
