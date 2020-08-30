<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequest extends Model
{
   public function order()
   {
   	return $this->belongsTo('App\Models\Order');
   }

   public function inventory()
   {
   	return $this->belongsTo('App\Models\Inventory');
   }

   public function getRequestCreatedAttribute($value)
   {
      $time = strtotime($value);
      return date('m/d/Y h:i A', $time);
   }
}
