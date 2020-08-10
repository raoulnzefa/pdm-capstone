<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    public function barangays()
    {
    	return $this->hasMany('App\Barangay');
    }
}
