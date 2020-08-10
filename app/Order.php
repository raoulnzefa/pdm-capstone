<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $primaryKey = 'number';
    public $keyType = 'string';
    public $incrementing = false;

    public function bankDepositSlip()
    {
        return $this->hasOne('App\BankDepositSlip');
    }

    public function bankDepositPayment()
    {
        return $this->hasOne('App\BankDepositPayment');
    }

    public function returnRequest()
    {
        return $this->hasOne('App\ReturnRequest');
    }

    public function cancelOrderRequest()
    {
        return $this->hasOne('App\CancelOrderRequest');
    }

    public function invoice()
    {
        return $this->hasOne('App\Invoice');
    }

    public function orderProducts()
    {
    	return $this->hasMany('App\OrderProduct');
    }

    public function customer()
    {
    	return $this->belongsTo('App\Customer');
    }

    public function getOrderSubtotalAttribute($value)
    {
        return number_format($value,2);
    }

    public function getOrderTotalAttribute($value)
    {
    	return number_format($value,2);
    }

    public function getOrderCreatedAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function getOrderCompletedAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function getOrderCancelledAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function getOrderWarrantyAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function storePickup()
    {
        return $this->hasOne('App\StorePickup');
    }

}
