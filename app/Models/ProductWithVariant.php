<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWithVariant extends Model
{
	public function product()
	{
		return $this->belongsTo('App\Product');
	}

	public function inventoryVariant()
	{
		return $this->hasOne('App\InventoryVariant');
	}	
}
