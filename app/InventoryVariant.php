<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryVariant extends Model
{
   public function productWithVariant()
   {
   	return $this->belongsTo('App\ProductWithVariant', 'variant_id');
   }

   public function inventory()
   {
   	return $this->belongsTo('App\Inventory');
   }
}
