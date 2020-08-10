<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function getPriceAttribute($value)
    {
    	return number_format($value, 2);
    }

    public function getTotalAttribute($value)
    {
    	return number_format($value, 2);
    }
}
