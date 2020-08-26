<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
   public function getVoucherStartAttribute($value)
   {
   	return Date('m/d/Y', strtotime($value));
   
   }

   public function getVoucherEndAttribute($value)
   {
   	return Date('m/d/Y', strtotime($value));
   
   }
}
