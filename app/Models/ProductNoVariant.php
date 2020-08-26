<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductNoVariant extends Model
{
   protected $primaryKey = 'inventory_number';
	public $incrementing = false;
	protected $keyType = 'string';

	public function product()
   {
      return $this->belongsTo('App\Models\Product', 'product_number');
   }

   public function inventory()
   {
      return $this->belongsTo('App\Models\Inventory', 'inventory_number');
   }

}
