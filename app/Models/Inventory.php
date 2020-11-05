<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
	protected $primaryKey = 'number';
    public $keyType = 'string';
    public $incrementing = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function replacementRequest()
    {
        return $this->hasOne('App\Models\ReplacementRequest');
    }

    public function productWithVariant()
    {
        return $this->hasOne('App\Models\ProductWithVariant','inventory_number');
    }
    
    public function productNoVariant()
    {
        return $this->hasOne('App\Models\ProductNoVariant','inventory_number');
    }

    public function orderProducts()
    {
    	return $this->hasMany('App\Models\OrderProduct');
    }

     public function invoiceProducts()
    {
        return $this->hasMany('App\Models\InvoiceProduct');
    }

}
