<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDepositSlip extends Model
{
    public function order()
    {
    	return $this->belongsTo('App\Order');
    }
}
