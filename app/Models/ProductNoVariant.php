<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductNoVariant extends Model
{
   protected $primaryKey = 'product_number';
	public $incrementing = false;
	protected $keyType = 'string';

	public function product()
   {
        return $this->belongsTo('App\Product');
   }
}
