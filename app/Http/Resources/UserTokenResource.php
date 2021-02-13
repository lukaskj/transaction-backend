<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTokenResource extends JsonResource {
   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
   public function toArray($request) {
      return [
         'token' => $this->token,
         'expire_date' => $this->expire_date->format('Y-m-d H:i:s'),
      ];
   }
}
