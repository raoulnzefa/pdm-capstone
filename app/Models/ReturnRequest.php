<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    public function returnProductRequests()
    {
    	return $this->hasMany('App\Models\ReturnProductRequest');
    }

    public function customer()
    {
    	return $this->belongsTo('App\Models\Customer');
    }

    public function order()
    {
    	return $this->belongsTo('App\Models\Order');
    }

    public function reason()
    {
    	return $this->belongsTo('App\Models\Reason');
    }

    public function getDateReturnRequestAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }
}
