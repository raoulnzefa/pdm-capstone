<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public function cancelledProduct()
    {
        return $this->hasOne('App\Models\CancelledProduct');
    }

    public function cancelProductRequest()
    {
        return $this->hasOne('App\Models\CancelProductRequest');
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

    public function inventory()
    {
        return $this->belongsTo('App\Models\Inventory','inventory_number');
    }
}
