<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
	public function inventory()
	{
		return $this->belongsTo('App\Models\Inventory');
	}

	public function getPriceAttribute($value)
    {
    	return number_format($value, 2);
    }

   	public function getShippingFeeAttribute($value)
    {
    	return number_format($value, 2);
    }

	public function invoice()
	{
		return $this->belongsTo('App\Models\Invoice');
	}

    public function getTotalAttribute($value)
    {
    	return number_format($value, 2);
    }

    public function getTotalAmountAttribute($value)
    {
        return number_format($value, 2);
    }

}
