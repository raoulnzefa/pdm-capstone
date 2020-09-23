<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $primaryKey = 'number';
    public $keyType = 'string';
    public $incrementing = false;

    public function bankDepositSlip()
    {
        return $this->hasOne('App\Models\BankDepositSlip');
    }

    public function bankDepositPayment()
    {
        return $this->hasOne('App\Models\BankDepositPayment');
    }

    public function cancelOrderRequest()
    {
        return $this->hasOne('App\Models\CancelOrderRequest');
    }

    public function invoice()
    {
        return $this->hasOne('App\Models\Invoice');
    }

    public function orderProducts()
    {
    	return $this->hasMany('App\Models\OrderProduct');
    }

    public function customer()
    {
    	return $this->belongsTo('App\Models\Customer');
    }

    public function getOrderSubtotalAttribute($value)
    {
        return number_format($value,2);
    }

    public function getOrderDiscountAttribute($value)
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

    public function getOrderForPickupAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function getOrderDuePaymentAttribute($value)
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
        return $this->hasOne('App\Models\StorePickup');
    }

    public function shipping()
    {
        return $this->hasOne('App\Models\ShippingAddress');
    }

    public function replacementRequest()
    {
        return $this->hasOne('App\Models\ReplacementRequest');
    }

}
