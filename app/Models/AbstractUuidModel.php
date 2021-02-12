<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbstractUuidModel extends Model {
   protected $primaryKey = 'id';

   public $incrementing = false;

   protected $keyType = 'string';

   protected static function boot() {
      parent::boot();
      static::creating(function (Model $model) {
         $model->{$model->getKeyName()} = (string) Str::uuid();
      });
   }
}
