<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    protected $primaryKey = 'number';
    public $keyType = 'string';
    public $incrementing = false;

    public function returnRequest()
    {
        return $this->hasOne('App\ReturnRequest');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function invoiceProducts()
    {
    	return $this->hasMany('App\InvoiceProduct');
    }

    public function getCreatedAttribute($value)
    {
    	$time = strtotime($value);
    	return date('m/d/Y', $time);
    }

    public function getWarrantyAttribute($value)
    {
        $time = strtotime($value);
        return date('m/d/Y', $time);
    }

    public function getSubtotalAttribute($value)
    {
    	return number_format($value, 2);
    }

    public function getDiscountAttribute($value)
    {
    	return number_format($value, 2);
    }

    public function getShippingFeeAttribute($value)
    {
    	return number_format($value, 2);
    }

    public function getTotalAttribute($value)
    {
    	return number_format($value, 2);
    }
}
