<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public function cancelledProduct()
    {
        return $this->hasOne('App\CancelledProduct');
    }

    public function cancelProductRequest()
    {
        return $this->hasOne('App\CancelProductRequest');
    }
    
	public function getPriceAttribute($value)
    {
    	return number_format($value,2);
    }

    public function getTotalAttribute($value)
    {
    	return number_format($value,2);
    }

    public function getTotalAmountAttribute($value)
    {
        return number_format($value, 2);
    }
}
