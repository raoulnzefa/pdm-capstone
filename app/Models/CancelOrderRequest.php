<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelOrderRequest extends Model
{
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

    public function cancelProductRequests()
    {
    	return $this->hasMany('App\Models\CancelProductRequest');
    }

    public function getDateRequestAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y h:i A', $time);
    }

}
