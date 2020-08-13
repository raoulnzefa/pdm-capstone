<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
	protected $primaryKey = 'number';
    public $keyType = 'string';
    public $incrementing = false;

    public function inventorying()
    {
    	return $this->morphTo();
    }

    public function product()
    {
    	return $this->belongsTo('App\Product');
    }

    public function orderProducts()
    {
    	return $this->hasMany('App\OrderProduct');
    }

     public function invoiceProducts()
    {
        return $this->hasMany('App\InvoiceProduct');
    }

    public function inventoryVariant()
    {
        return $this->hasOne('App\InventoryVariant');
    }
}
