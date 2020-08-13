<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    public function returnProductRequests()
    {
    	return $this->hasMany('App\ReturnProductRequest');
    }

    public function customer()
    {
    	return $this->belongsTo('App\Customer');
    }

    public function order()
    {
    	return $this->belongsTo('App\Order');
    }

    public function reason()
    {
    	return $this->belongsTo('App\Reason');
    }

    public function getDateReturnRequestAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }
}
