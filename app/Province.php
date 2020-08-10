<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public function municipalities()
    {
    	return $this->hasMany('App\Municipality');
    }
}
