<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefectiveProduct extends Model
{
   public function inventory()
   {
   	return $this->belongsTo('App\Models\Inventory');
   }

   public function replacementRequest()
   {
   	return $this->belongsTo('App\Models\ReplacementRequest');
   }
}
