<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelOrderRequest extends Model
{
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

    public function cancelProductRequests()
    {
    	return $this->hasMany('App\CancelProductRequest');
    }

    public function getDateRequestAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y h:i A', $time);
    }

    public function refundCancelOrder()
    {
        return $this->hasOne('App\refundCancelOrder');
    }
}
