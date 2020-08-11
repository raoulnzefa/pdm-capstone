<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductWithVariant extends Model
{
   protected $primaryKey = 'inventory_number';
	public $incrementing = false;
	protected $keyType = 'string';
}
