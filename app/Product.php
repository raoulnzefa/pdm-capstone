<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

  protected $primaryKey = 'number';
	public $incrementing = false;
	protected $keyType = 'string';

   public function category()
   {
        return $this->belongsTo('App\Category');
   }

   public function productNoVariant()
   {
      return $this->hasOne('App\ProductNoVariant');
   }

   public function productWithVariants()
   {
      return $this->hasMany('App\ProductWithVariant');
   }

   public function getProductPriceAttribute($value)
   {
   	return number_format($value, 2);
   }

}
