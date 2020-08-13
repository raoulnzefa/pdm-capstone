<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnProductRequest extends Model
{
    public function returnRequest()
    {
    	return $this->belongsTo('App\ReturnRequest');
    }

    public function orderProduct()
    {
    	return $this->belongsTo('App\OrderProduct');
    }

    public function getAmountAttribute($value)
    {
    	return number_format($value, 2);
    }
}
