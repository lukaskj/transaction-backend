<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UuidModel {
   protected static function boot() {
      parent::boot();
      static::registerUuidCreatingEvent();
   }

   /**
    * Register Uuid generation event
    */
   private static function registerUuidCreatingEvent(): void {
      static::creating(function (Model $model) {
         $model->{$model->getKeyName()} = (string) Str::uuid();
      });
   }
}