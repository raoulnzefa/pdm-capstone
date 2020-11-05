<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementRequest extends Model
{
   public function replacementProductPhotos()
   {
      return $this->hasMany('App\Models\ReplacementProductPhoto');
   }

   public function order()
   {
   	return $this->belongsTo('App\Models\Order');
   }

   public function inventory()
   {
   	return $this->belongsTo('App\Models\Inventory');
   }

   public function product()
   {
      return $this->belongsTo('App\Models\Product', 'product_number');
   }

   public function customer()
   {
      return $this->belongsTo('App\Models\Customer');
   }

   public function getRequestCreatedAttribute($value)
   {
      $time = strtotime($value);
      return date('m/d/Y h:i A', $time);
   }
}
