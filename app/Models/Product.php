<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

  protected $primaryKey = 'number';
	public $incrementing = false;
	protected $keyType = 'string';

   public function category()
   {
      return $this->belongsTo('App\Models\Category');
   }

   public function productNoVariant()
   {
      return $this->hasOne('App\Models\ProductNoVariant');
   }

   public function productWithVariants()
   {
      return $this->hasMany('App\Models\ProductWithVariant');
   }

}
